@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700">👤 My Profile</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-600 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-600 text-red-800 rounded">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">New Password <span class="text-gray-400 text-xs">(leave blank to keep current password)</span></label>
            <input id="password" type="password" name="password" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            💾 Save Profile
        </button>
    </form>
</div>
@endsection
