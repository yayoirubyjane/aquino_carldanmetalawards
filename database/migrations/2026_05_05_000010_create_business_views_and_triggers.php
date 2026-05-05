<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS vw_OrderSummary');
        DB::unprepared('DROP VIEW IF EXISTS vw_StockStatus');
        DB::unprepared('DROP VIEW IF EXISTS vw_ProductionStatus');
        DB::unprepared('DROP VIEW IF EXISTS vw_PaymentDetails');
        DB::unprepared('DROP VIEW IF EXISTS vw_OrderItems');
        DB::unprepared('DROP VIEW IF EXISTS vw_ClientOrders');
        DB::unprepared('DROP VIEW IF EXISTS vw_ProductMaterials');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_StockInsert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_StockUpdateQuantity');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_AutoCreateProduction');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_ValidatePaymentAmount');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_DeductStockOnProduction');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_UpdateOrderStatus');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_PreventSkippingSteps');

        DB::unprepared("
            CREATE TRIGGER trg_StockInsert
            BEFORE INSERT ON stocks
            FOR EACH ROW
            BEGIN
                SET NEW.Quantity = NEW.StockIN - NEW.StockOUT;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_StockUpdateQuantity
            BEFORE UPDATE ON stocks
            FOR EACH ROW
            BEGIN
                SET NEW.Quantity = NEW.StockIN - NEW.StockOUT;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_ValidatePaymentAmount
            BEFORE INSERT ON payments
            FOR EACH ROW
            BEGIN
                IF NEW.Amount <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Payment amount must be greater than zero.';
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_AutoCreateProduction
            AFTER INSERT ON product_orders
            FOR EACH ROW
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM productions
                    WHERE OrderID = NEW.OrderID
                      AND ProductID = NEW.ProductID
                ) THEN
                    INSERT INTO productions (OrderID, ProductID, ProdStatus, ProdNote, ProdStartDate, ProdFinishedDate, created_at, updated_at)
                    VALUES (
                        NEW.OrderID,
                        NEW.ProductID,
                        'Not Started',
                        NULL,
                        COALESCE((SELECT OrderDate FROM orders WHERE OrderID = NEW.OrderID), CURDATE()),
                        NULL,
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_PreventSkippingSteps
            BEFORE UPDATE ON productions
            FOR EACH ROW
            BEGIN
                IF OLD.ProdStatus = 'Not Started' AND NEW.ProdStatus = 'Finished' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Production cannot skip from Not Started to Finished.';
                END IF;

                IF OLD.ProdStatus = 'Finished' AND NEW.ProdStatus <> 'Finished' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Finished production cannot go back to an earlier status.';
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_DeductStockOnProduction
            BEFORE UPDATE ON productions
            FOR EACH ROW
            BEGIN
                DECLARE done INT DEFAULT 0;
                DECLARE v_material_id BIGINT;
                DECLARE v_required_per_unit INT;
                DECLARE v_needed INT;
                DECLARE v_available INT;
                DECLARE v_stock_id BIGINT;
                DECLARE v_row_available INT;
                DECLARE stock_done INT DEFAULT 0;

                DECLARE material_cursor CURSOR FOR
                    SELECT pm.Material_ID, pm.RequiredQuantity
                    FROM product_material pm
                    WHERE pm.ProductID = NEW.ProductID;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

                IF OLD.ProdStatus <> 'In Progress' AND NEW.ProdStatus = 'In Progress' THEN
                    OPEN material_cursor;

                    material_loop: LOOP
                        FETCH material_cursor INTO v_material_id, v_required_per_unit;
                        IF done = 1 THEN
                            LEAVE material_loop;
                        END IF;

                        SET v_needed = v_required_per_unit * (
                            SELECT COALESCE(SUM(Quantity), 0)
                            FROM product_orders
                            WHERE OrderID = NEW.OrderID
                              AND ProductID = NEW.ProductID
                        );

                        SET v_available = (
                            SELECT COALESCE(SUM(Quantity), 0)
                            FROM stocks
                            WHERE Material_ID = v_material_id
                        );

                        IF v_available < v_needed THEN
                            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock available for production.';
                        END IF;

                        WHILE v_needed > 0 DO
                            SELECT s.StockID, s.Quantity
                            INTO v_stock_id, v_row_available
                            FROM stocks s
                            WHERE s.Material_ID = v_material_id
                              AND s.Quantity > 0
                            ORDER BY s.StockID
                            LIMIT 1;

                            IF v_row_available >= v_needed THEN
                                UPDATE stocks
                                SET StockOUT = StockOUT + v_needed
                                WHERE StockID = v_stock_id;

                                SET v_needed = 0;
                            ELSE
                                UPDATE stocks
                                SET StockOUT = StockOUT + v_row_available
                                WHERE StockID = v_stock_id;

                                SET v_needed = v_needed - v_row_available;
                            END IF;
                        END WHILE;
                    END LOOP;

                    CLOSE material_cursor;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_UpdateOrderStatus
            AFTER UPDATE ON productions
            FOR EACH ROW
            BEGIN
                IF NEW.ProdStatus = 'In Progress' THEN
                    UPDATE orders
                    SET OrderStatus = 'In Production'
                    WHERE OrderID = NEW.OrderID;
                END IF;

                IF NEW.ProdStatus = 'Finished' THEN
                    IF EXISTS (
                        SELECT 1
                        FROM productions
                        WHERE OrderID = NEW.OrderID
                          AND ProdStatus <> 'Finished'
                    ) THEN
                        UPDATE orders
                        SET OrderStatus = 'In Production'
                        WHERE OrderID = NEW.OrderID;
                    ELSE
                        UPDATE orders
                        SET OrderStatus = 'Completed'
                        WHERE OrderID = NEW.OrderID;
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE VIEW vw_OrderSummary AS
            SELECT
                o.OrderID,
                o.OrderDate,
                o.DeliveryDate,
                o.OrderStatus,
                CONCAT(c.ClientFN, ' ', COALESCE(c.ClientMN, ''), ' ', c.ClientLN) AS ClientFullName,
                CONCAT(e.EmployeeFN, ' ', COALESCE(e.EmployeeMN, ''), ' ', e.EmployeeLN) AS EmployeeFullName
            FROM orders o
            INNER JOIN clients c ON c.ClientID = o.ClientID
            INNER JOIN employees e ON e.EmployeeID = o.EmployeeID
        ");

        DB::unprepared("
            CREATE VIEW vw_StockStatus AS
            SELECT
                s.StockID,
                m.MaterialName,
                m.MaterialType,
                m.UnitCost,
                sp.SupplierName,
                sp.SupplierContact,
                sp.SupplierAddress,
                s.StockIN,
                s.StockOUT,
                s.Quantity
            FROM stocks s
            INNER JOIN materials m ON m.Material_ID = s.Material_ID
            INNER JOIN suppliers sp ON sp.SupplierID = s.SupplierID
        ");

        DB::unprepared("
            CREATE VIEW vw_ProductionStatus AS
            SELECT
                pr.ProductionID,
                pr.OrderID,
                pr.ProductID,
                p.ProductName,
                p.ProductType,
                o.OrderDate,
                o.OrderStatus,
                pr.ProdStatus,
                pr.ProdStartDate,
                pr.ProdFinishedDate
            FROM productions pr
            INNER JOIN products p ON p.ProductID = pr.ProductID
            INNER JOIN orders o ON o.OrderID = pr.OrderID
        ");

        DB::unprepared("
            CREATE VIEW vw_PaymentDetails AS
            SELECT
                pay.PaymentID,
                pay.OrderID,
                pay.PaymentMethod,
                pay.PaymentDate,
                pay.Amount,
                pay.ReferenceNumber,
                o.OrderStatus,
                CONCAT(c.ClientFN, ' ', COALESCE(c.ClientMN, ''), ' ', c.ClientLN) AS ClientFullName,
                CONCAT(e.EmployeeFN, ' ', COALESCE(e.EmployeeMN, ''), ' ', e.EmployeeLN) AS EmployeeFullName
            FROM payments pay
            INNER JOIN orders o ON o.OrderID = pay.OrderID
            INNER JOIN clients c ON c.ClientID = o.ClientID
            INNER JOIN employees e ON e.EmployeeID = pay.EmployeeID
        ");

        DB::unprepared("
            CREATE VIEW vw_OrderItems AS
            SELECT
                po.ProductOrderID,
                po.OrderID,
                p.ProductName,
                p.ProductType,
                po.Quantity,
                po.Price,
                (po.Quantity * po.Price) AS TotalItemCost
            FROM product_orders po
            INNER JOIN products p ON p.ProductID = po.ProductID
        ");

        DB::unprepared("
            CREATE VIEW vw_ClientOrders AS
            SELECT
                c.ClientID,
                CONCAT(c.ClientFN, ' ', COALESCE(c.ClientMN, ''), ' ', c.ClientLN) AS ClientFullName,
                o.OrderID,
                o.OrderDate,
                o.OrderStatus
            FROM clients c
            INNER JOIN orders o ON o.ClientID = c.ClientID
        ");

        DB::unprepared("
            CREATE VIEW vw_ProductMaterials AS
            SELECT
                p.ProductID,
                p.ProductName,
                m.MaterialName,
                m.MaterialType,
                m.UnitCost,
                pm.RequiredQuantity,
                (m.UnitCost * pm.RequiredQuantity) AS TotalMaterialCost
            FROM products p
            INNER JOIN product_material pm ON pm.ProductID = p.ProductID
            INNER JOIN materials m ON m.Material_ID = pm.Material_ID
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS vw_OrderSummary');
        DB::unprepared('DROP VIEW IF EXISTS vw_StockStatus');
        DB::unprepared('DROP VIEW IF EXISTS vw_ProductionStatus');
        DB::unprepared('DROP VIEW IF EXISTS vw_PaymentDetails');
        DB::unprepared('DROP VIEW IF EXISTS vw_OrderItems');
        DB::unprepared('DROP VIEW IF EXISTS vw_ClientOrders');
        DB::unprepared('DROP VIEW IF EXISTS vw_ProductMaterials');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_StockInsert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_StockUpdateQuantity');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_AutoCreateProduction');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_ValidatePaymentAmount');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_DeductStockOnProduction');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_UpdateOrderStatus');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_PreventSkippingSteps');
    }
};
