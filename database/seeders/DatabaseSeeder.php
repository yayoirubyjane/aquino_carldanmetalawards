<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('clients')->insert([
            [
                'ClientFN' => 'Juan',
                'ClientMN' => 'D.',
                'ClientLN' => 'Dela Cruz',
                'ClientContact' => '09123456789',
                'ClientStreet' => '28 Sampaguita Street',
                'ClientBarangay' => 'Matina',
                'ClientCity' => 'Davao City',
                'ClientProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ClientFN' => 'Maria',
                'ClientMN' => 'S.',
                'ClientLN' => 'Santos',
                'ClientContact' => '09987654321',
                'ClientStreet' => '15 Narra Avenue',
                'ClientBarangay' => 'Buhangin',
                'ClientCity' => 'Davao City',
                'ClientProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ClientFN' => 'Carlos',
                'ClientMN' => 'R.',
                'ClientLN' => 'Reyes',
                'ClientContact' => '09191234567',
                'ClientStreet' => '8 Mabini Street',
                'ClientBarangay' => 'Toril',
                'ClientCity' => 'Davao City',
                'ClientProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('employees')->insert([
            [
                'EmployeeFN' => 'Dexter',
                'EmployeeMN' => 'B.',
                'EmployeeLN' => 'Aquino',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeFN' => 'Lara',
                'EmployeeMN' => 'M.',
                'EmployeeLN' => 'Cruz',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('suppliers')->insert([
            [
                'SupplierName' => 'Skyline Acrylic Supply',
                'SupplierContact' => '09170000001',
                'SupplierStreet' => '101 J.P. Laurel Avenue',
                'SupplierBarangay' => 'Lanang',
                'SupplierCity' => 'Davao City',
                'SupplierProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'SupplierName' => 'Northwood Crafts',
                'SupplierContact' => '09170000002',
                'SupplierStreet' => '45 Daang Maharlika',
                'SupplierBarangay' => 'Panacan',
                'SupplierCity' => 'Davao City',
                'SupplierProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'SupplierName' => 'Victory Metal Works',
                'SupplierContact' => '09170000003',
                'SupplierStreet' => '12 Palma Gil Street',
                'SupplierBarangay' => 'Bajada',
                'SupplierCity' => 'Davao City',
                'SupplierProvince' => 'Davao del Sur',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('materials')->insert([
            ['MaterialName' => 'Premium Acrylic Sheet', 'MaterialType' => 'Acrylic', 'UnitCost' => 500.00, 'created_at' => $now, 'updated_at' => $now],
            ['MaterialName' => 'Mahogany Wood Base', 'MaterialType' => 'Wood', 'UnitCost' => 350.00, 'created_at' => $now, 'updated_at' => $now],
            ['MaterialName' => 'Metal Plate Insert', 'MaterialType' => 'Metal', 'UnitCost' => 150.00, 'created_at' => $now, 'updated_at' => $now],
            ['MaterialName' => 'Ribbon Lanyard', 'MaterialType' => 'Accessory', 'UnitCost' => 40.00, 'created_at' => $now, 'updated_at' => $now],
            ['MaterialName' => 'Zinc Medal Blank', 'MaterialType' => 'Metal', 'UnitCost' => 120.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('stocks')->insert([
            ['SupplierID' => 1, 'Material_ID' => 1, 'StockIN' => 100, 'StockOUT' => 0, 'Quantity' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['SupplierID' => 2, 'Material_ID' => 2, 'StockIN' => 60, 'StockOUT' => 0, 'Quantity' => 60, 'created_at' => $now, 'updated_at' => $now],
            ['SupplierID' => 3, 'Material_ID' => 3, 'StockIN' => 150, 'StockOUT' => 0, 'Quantity' => 150, 'created_at' => $now, 'updated_at' => $now],
            ['SupplierID' => 1, 'Material_ID' => 4, 'StockIN' => 250, 'StockOUT' => 0, 'Quantity' => 250, 'created_at' => $now, 'updated_at' => $now],
            ['SupplierID' => 3, 'Material_ID' => 5, 'StockIN' => 200, 'StockOUT' => 0, 'Quantity' => 200, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('products')->insert([
            ['ProductName' => 'Champion Trophy', 'ProductType' => 'Trophy', 'Price' => 3200.00, 'created_at' => $now, 'updated_at' => $now],
            ['ProductName' => 'Recognition Plaque', 'ProductType' => 'Plaque', 'Price' => 1800.00, 'created_at' => $now, 'updated_at' => $now],
            ['ProductName' => 'Achievement Medal', 'ProductType' => 'Medal', 'Price' => 250.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('product_material')->insert([
            ['ProductID' => 1, 'Material_ID' => 1, 'RequiredQuantity' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 1, 'Material_ID' => 2, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 1, 'Material_ID' => 3, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],

            ['ProductID' => 2, 'Material_ID' => 1, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 2, 'Material_ID' => 2, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 2, 'Material_ID' => 3, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],

            ['ProductID' => 3, 'Material_ID' => 3, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 3, 'Material_ID' => 4, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ProductID' => 3, 'Material_ID' => 5, 'RequiredQuantity' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('orders')->insert([
            [
                'EmployeeID' => 1,
                'ClientID' => 1,
                'OrderStatus' => 'Pending',
                'OrderDate' => now()->subDays(4)->toDateString(),
                'DeliveryDate' => now()->addDays(5)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeID' => 2,
                'ClientID' => 2,
                'OrderStatus' => 'Pending',
                'OrderDate' => now()->subDays(3)->toDateString(),
                'DeliveryDate' => now()->addDays(4)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeID' => 1,
                'ClientID' => 3,
                'OrderStatus' => 'Pending',
                'OrderDate' => now()->subDays(7)->toDateString(),
                'DeliveryDate' => now()->subDay()->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('product_orders')->insert([
            [
                'OrderID' => 1,
                'ProductID' => 2,
                'Quantity' => 1,
                'Price' => 1800.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'OrderID' => 1,
                'ProductID' => 3,
                'Quantity' => 10,
                'Price' => 250.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'OrderID' => 2,
                'ProductID' => 1,
                'Quantity' => 2,
                'Price' => 3200.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'OrderID' => 3,
                'ProductID' => 3,
                'Quantity' => 20,
                'Price' => 250.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('payments')->insert([
            [
                'EmployeeID' => 1,
                'OrderID' => 1,
                'PaymentMethod' => 'Cash',
                'PaymentDate' => now()->subDays(4)->toDateString(),
                'Amount' => 2150.00,
                'ReferenceNumber' => 'DP-1001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeID' => 2,
                'OrderID' => 2,
                'PaymentMethod' => 'Bank Transfer',
                'PaymentDate' => now()->subDays(3)->toDateString(),
                'Amount' => 3200.00,
                'ReferenceNumber' => 'DP-1002',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeID' => 1,
                'OrderID' => 3,
                'PaymentMethod' => 'Cash',
                'PaymentDate' => now()->subDays(7)->toDateString(),
                'Amount' => 2500.00,
                'ReferenceNumber' => 'DP-1003',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'EmployeeID' => 1,
                'OrderID' => 3,
                'PaymentMethod' => 'Cash',
                'PaymentDate' => now()->subDay()->toDateString(),
                'Amount' => 2500.00,
                'ReferenceNumber' => 'FP-1003',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('productions')
            ->where('OrderID', 2)
            ->update([
                'ProdStatus' => 'In Progress',
                'ProdNote' => 'Materials released to production floor.',
                'ProdStartDate' => now()->subDays(2)->toDateString(),
                'updated_at' => $now,
            ]);

        DB::table('productions')
            ->where('OrderID', 3)
            ->update([
                'ProdStatus' => 'In Progress',
                'ProdNote' => 'Quality checked and assembled.',
                'ProdStartDate' => now()->subDays(6)->toDateString(),
                'updated_at' => $now,
            ]);

        DB::table('productions')
            ->where('OrderID', 3)
            ->update([
                'ProdStatus' => 'Finished',
                'ProdNote' => 'Delivered to client and final payment collected.',
                'ProdFinishedDate' => now()->subDay()->toDateString(),
                'updated_at' => $now,
            ]);
    }
}
