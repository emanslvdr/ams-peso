<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('user.layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            function openDeleteModal(action) {
    let modal = document.getElementById('deleteModal');
    let modalContent = modal.children[0];

    document.getElementById('deleteForm').setAttribute('action', action);

    // Show modal with fade-in effect
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100');

    // Scale up content
    modalContent.classList.remove('scale-95');
    modalContent.classList.add('scale-100');
}

function closeDeleteModal() {
    let modal = document.getElementById('deleteModal');
    let modalContent = modal.children[0];

    // Fade-out effect
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');

    // Scale down content
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    // Hide modal completely after transition
    setTimeout(() => {
        modal.classList.add('invisible');
    }, 300);
}
        </script>
    </body>
</html>
