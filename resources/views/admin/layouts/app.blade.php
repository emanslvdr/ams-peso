<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PESO') }}</title>
    <link rel="stylesheet" href="{{ asset('admin/style/style.css') }}">
    <script type="text/javascript" src="{{ asset('admin/js/app.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('admin.layouts.navigation')
    <main>
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        {{ $slot }}
    </main>
</body>

</html>