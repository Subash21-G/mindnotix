@extends('layouts.app')

@section('content')
<div class="flex mt-10 max-w-6xl mx-auto">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white rounded-xl shadow-lg p-6 mr-8 hidden md:block">
        <div class="flex flex-col items-center pb-6 border-b mb-6">
            <div class="bg-indigo-50 rounded-full w-20 h-20 flex items-center justify-center text-3xl text-indigo-600 font-bold mb-2">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">@ {{ $user->username }}</p>
            <p class="text-gray-500 text-xs">{{ $user->email }}</p>
        </div>
        <div class="mb-6">
            <h3 class="text-indigo-600 font-bold text-2xl text-center">{{ $formCount }}</h3>
            <p class="text-gray-700 text-center text-sm">Forms Created</p>
        </div>
        <a href="{{ route('profile.index') }}" class="block bg-indigo-100 text-indigo-700 font-medium rounded px-3 py-2 mt-4 text-center hover:bg-indigo-200 transition">View Profile</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="w-full text-red-600 hover:underline mt-2 text-center">Logout</button>
        </form>
    </aside>

    <!-- DASHBOARD MAIN BODY -->
    <main class="flex-1">
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">My Forms</h2>
                <a href="{{ route('forms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">➕ Create New Form</a>
            </div>
            @if($forms->isEmpty())
                <div class="text-center text-gray-600 bg-gray-50 p-10 rounded-lg">
                    <h3 class="text-xl font-semibold">No forms yet!</h3>
                    <p class="mt-2">Create your first form now.</p>
                    <a href="{{ route('forms.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Create Form</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($forms as $form)
                        @php
                            $header = is_array($form->header ?? null) ? $form->header : [];
                            $displayTitle = !empty($header['title']) && is_string($header['title'])
                                            ? $header['title']
                                            : ($form->title ?? 'Untitled Form');
                        @endphp

                        <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $displayTitle }}</h3>
                            <p class="text-sm text-gray-700 mb-4 break-words">{{ Str::limit($form->description, 100) }}</p>

                            {{-- ======================= NEW CODE START ======================= --}}
                            <div class="mt-auto">
                                <div class="mb-4">
                                    <label for="share-link-{{ $form->id }}" class="text-sm font-medium text-gray-600 mb-1 block">Shareable Link</label>
                                    <div class="flex gap-2">
                                        <input
                                            id="share-link-{{ $form->id }}"
                                            type="text"
                                            class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-sm px-2"
                                            value="{{ route('forms.public.show', $form) }}"
                                            readonly
                                        >
                                        <button
                                            onclick="copyLink('share-link-{{ $form->id }}', this)"
                                            class="bg-gray-200 text-gray-700 px-3 rounded-md text-sm hover:bg-gray-300 transition"
                                        >
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            {{-- ======================= NEW CODE END ========================= --}}

                                <div class="flex flex-wrap justify-end gap-4 border-t pt-4">
                                    <a href="{{ route('forms.public.show', $form) }}" class="text-blue-600 hover:underline text-sm font-medium">🔗 Preview</a>
                                    <a href="{{ route('responses.index', $form) }}" class="text-green-600 hover:underline text-sm font-medium">📊 Responses</a>
                                    <a href="{{ route('forms.edit', $form) }}" class="text-indigo-500 hover:underline text-sm font-medium">✏️ Edit</a>
                                    <form action="{{ route('forms.destroy', $form) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this form and all its responses?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline text-sm font-medium" type="submit">🗑️ Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</div>

{{-- NEW JAVASCRIPT FUNCTION (place at the end of the file) --}}
<script>
    function copyLink(inputId, buttonElement) {
        const input = document.getElementById(inputId);
        input.select(); // Select the text
        input.setSelectionRange(0, 99999); // For mobile devices

        // Use the modern Clipboard API
        navigator.clipboard.writeText(input.value).then(() => {
            // Provide feedback to the user
            const originalText = buttonElement.innerText;
            buttonElement.innerText = 'Copied!';
            setTimeout(() => {
                buttonElement.innerText = originalText;
            }, 2000); // Revert back after 2 seconds
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy link.');
        });
    }
</script>
@endsection
