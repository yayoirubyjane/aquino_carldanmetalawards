<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production System - Carldan Metal Awards</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 text-gray-800">
    <nav class="mb-8 flex gap-6 border-b pb-4">
        <h1 class="font-bold text-xl text-orange-600 mr-4">Carldan Production</h1>
        <a href="{{ route('materials.index') }}" class="text-blue-600 font-semibold hover:underline">Materials</a>
        <a href="{{ route('products.index') }}" class="text-blue-600 font-semibold hover:underline">Products</a>
        <a href="{{ route('orders.index') }}" class="text-blue-600 font-semibold hover:underline">Orders</a>
        <a href="{{ route('productions.index') }}" class="text-blue-600 font-semibold hover:underline">Production Tracking</a>
    </nav>

    <div class="bg-white p-6 rounded-lg shadow-md">
        @yield('content')
    </div>
</body>
</html>
