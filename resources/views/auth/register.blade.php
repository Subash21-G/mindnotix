<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-500">
    <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-2xl p-8 shadow-lg w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-6 text-white">📝 Register</h2>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-left">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="sr-only">Full Name</label>
                <input type="text" id="name" name="name" placeholder="👤 Full Name" value="{{ old('name') }}" class="w-full p-3 rounded-lg focus:outline-none" required autofocus>
            </div>
            <div>
                <label for="username" class="sr-only">Username</label>
                <input type="text" id="username" name="username" placeholder="🆔 Username" value="{{ old('username') }}" class="w-full p-3 rounded-lg focus:outline-none" required>
            </div>
            <div>
                <label for="email" class="sr-only">Email</label>
                <input type="email" id="email" name="email" placeholder="📧 Email" value="{{ old('email') }}" class="w-full p-3 rounded-lg focus:outline-none" required>
            </div>
            <div>
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" placeholder="🔑 Password" class="w-full p-3 rounded-lg focus:outline-none" required>
            </div>
            <div>
                <label for="password_confirmation" class="sr-only">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="✅ Confirm Password" class="w-full p-3 rounded-lg focus:outline-none" required>
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">Register</button>
        </form>
        <div class="flex items-center my-4">
            <hr class="flex-grow border-gray-300">
            <span class="mx-4 text-white font-semibold">OR</span>
            <hr class="flex-grow border-gray-300">
        </div>
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-2 bg-white text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition">
            <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></svg>
            Continue with Google
        </a>
        <p class="text-white mt-6 text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="underline hover:text-purple-200">Login here</a>
        </p>
    </div>
</body>
</html>
