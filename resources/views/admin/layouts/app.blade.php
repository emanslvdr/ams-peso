<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PESO') }}</title>
    <link rel="stylesheet" href="{{ asset('admin/style/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('admin/js/app.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('admin.layouts.navigation')

    <main>

        @isset($header)
        <header class="gradient-background shadow rounded-lg">
            <div class="text-end mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        {{ $slot }}

    </main>

</body>
<script src="//unpkg.com/alpinejs" defer></script>
</html>