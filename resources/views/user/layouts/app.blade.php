<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PESO') }}</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('user/style/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('user/js/app.js') }}" defer></script>
  
    </head>

    <body class="gradient-background">
        <div class="min-h-screen">
            @include('user.layouts.navigation')
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
        <script src="//unpkg.com/alpinejs" defer></script>

    </body>
</html>
