<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token for AJAX requests (like in the form builder) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dynamic Form Builder</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-white to-indigo-100 min-h-screen text-gray-800 flex flex-col">

    <nav class="bg-white/70 backdrop-blur-md shadow-sm py-4 px-6 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-semibold text-indigo-600">📋 FormBuilder</a>

            @auth
                <div class="flex items-center gap-4">
                    <!-- Username is now a link to the profile page -->
                    <a href="{{ route('profile.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
                        👤 {{ Auth::user()->username }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700">Register</a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="px-4 flex-grow">
        @yield('content')
    </main>

    <footer class="mt-10 py-6 text-center text-sm text-gray-500">
        © {{ date('Y') }} FormBuilder by Gsubash Bose
    </footer>

</body>
</html>
