<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>PGECC</title>

    @vite('resources/css/app.css')
    @livewireStyles
    @stack('page-css')
</head>

<!-- <body class="background-color relative"> -->
<body class="bg-gray-100 font-sans text-gray-800 antialiased">

    <!-- Notifications -->
    <div class="notification-container" id="notification-container">   
        @if(session('notification'))
            @foreach(session('notification.messages') as $message)
                <x-notification>
                    <x-slot name="header">{{ session('notification.title') }}</x-slot>
                    <x-slot name="body">{{ $message[0] }}</x-slot>
                    <x-slot name="footer">Just Now</x-slot>
                </x-notification>
            @endforeach
        @endif
    </div>

    <!-- Future Sidebar -->
    @yield('sidebar')

    <!-- Main Content Section -->
    <div class="content">
        @yield('content')
    </div>

    <script>
        window.__CSRF_TOKEN__ = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    @vite('resources/js/app.js')
    
    @livewireScripts

    @stack('scripts')
    @yield('modal-scripts')
</body>
</html>
