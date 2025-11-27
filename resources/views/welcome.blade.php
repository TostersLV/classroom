<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ClassIt') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="flex justify-between items-center px-6 py-4">
                <div class="text-white text-2xl font-bold">
                    ClassIt
                </div>

                <div class="flex gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-white border border-white rounded-lg hover:bg-white hover:text-gray-900 transition">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Main Content -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-6xl md:text-7xl font-bold text-white mb-6">
                        Welcome to ClassIt
                    </h1>
                    <p class="text-xl text-gray-400 mb-12">
                        Your all-in-one classroom management platform
                    </p>
                    
                    @if (Route::has('register') && !Auth::check())
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white text-lg rounded-lg hover:bg-indigo-700 transition">
                            Get Started
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>