<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carldan Metal Awards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell">
    @php
        $currentRoute = request()->route()?->getName();

        $navItems = [
            'products.index' => 'Products',
            'suppliers.index' => 'Suppliers',
            'stocks.index' => 'Inventory',
            'orders.index' => 'Orders',
            'productions.index' => 'Production',
            'payments.index' => 'Sales & Payments',
        ];
    @endphp

    <nav class="page-nav">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-500">Carldan Metal Awards</p>
                <h1 class="text-2xl font-bold text-slate-900">Production Management System</h1>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ($navItems as $routeName => $label)
                    <a
                        href="{{ route($routeName) }}"
                        class="page-nav-link {{ str_starts_with($currentRoute ?? '', explode('.', $routeName)[0]) ? 'page-nav-link-active' : '' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
