@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    @php
    $header = is_array($form->header ?? null) ? $form->header : [];
    $themeColor = $header['theme_color'] ?? '#6366f1';
    $align = $header['title_align'] ?? 'center';
    $headerImage = $header['image'] ?? null;
    $logo = $header['logo'] ?? null;
    $title = !empty($header['title']) && is_string($header['title']) ? $header['title'] : ($form->title ?? 'Untitled Form');
    $subtitle = !empty($header['subtitle']) && is_string($header['subtitle']) ? $header['subtitle'] : null;
@endphp

<header class="mb-8 flex flex-col items-center text-center">
    @if($logo)
        <img src="{{ $logo }}" class="w-20 h-20 object-contain mx-auto mb-3" alt="Company Logo" />
    @endif
    @if($headerImage)
        <img src="{{ $headerImage }}" class="mb-4 w-32 h-32 mx-auto object-cover rounded-full border-4 border-indigo-200 shadow" alt="Form Banner"/>
    @endif
    <h2 class="text-3xl font-bold mb-1" style="color: {{ $themeColor }}; text-align:{{ $align }}">
        {{ $title }}
    </h2>
    @if($subtitle)
        <span class="text-base text-gray-500">{{ $subtitle }}</span>
    @endif
</header>

    <h2 class="text-2xl font-bold mb-6">Responses for: "{{ $form->title }}"</h2>
    @if(session('success'))
        <div class="bg-green-50 rounded p-3 mb-4 text-green-800">{{ session('success') }}</div>
    @endif

    @if($responses->isEmpty())
        <div class="text-gray-500 bg-gray-100 p-6 rounded-lg text-center">No responses yet.</div>
    @else
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">#</th>
                        @foreach($form->fields as $field)
                            <th class="px-4 py-2 border-b">{{ $field['label'] ?? $field['name'] ?? 'Field' }}</th>
                        @endforeach
                        <th class="px-4 py-2 border-b">Submitted</th>
                        <th class="px-4 py-2 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($responses as $i => $response)
                        <tr>
                            <td class="px-2 py-2 border-b">{{ $responses->firstItem() + $i }}</td>
                            @foreach($form->fields as $field)
                                <td class="px-2 py-2 border-b">
                                    @php
                                        $name = $field['name'] ?? '';
                                        $v = $response->answers[$name] ?? '';
                                        echo is_array($v) ? implode(', ', $v) : e($v);
                                    @endphp
                                </td>
                            @endforeach
                            <td class="px-2 py-2 border-b text-xs">{{ $response->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-2 py-2 border-b">

<form action="{{ route('responses.destroy', [$form, $response->id]) }}" method="POST" onsubmit="return confirm('Delete this response?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline" type="submit" title="Delete Response">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $responses->links() }}</div>
    @endif
    <a href="{{ route('dashboard') }}" class="mt-8 inline-block text-indigo-700 hover:underline">&larr; Back to Dashboard</a>



</div>

@php
    $footer = is_array($form->footer ?? null) ? $form->footer : [];
    $showFooter = !isset($footer['show_footer']) || $footer['show_footer'];
@endphp
@if($showFooter)
<footer class="mt-10 mb-3 text-center text-gray-600 space-y-3">
    @if(!empty($footer['website']))
        <div>
            <a href="{{ $footer['website'] }}" target="_blank" rel="noopener" class="underline hover:text-indigo-500 transition">
                🌐 {{ parse_url($footer['website'], PHP_URL_HOST) ?? $footer['website'] }}
            </a>
        </div>
    @endif

    @if(!empty($footer['support_numbers']) && is_array($footer['support_numbers']))
        <div>
            @foreach($footer['support_numbers'] as $number)
                @if($number)
                <span class="inline-block mr-2">☎️ <a href="tel:{{ $number }}" class="underline hover:text-indigo-500">{{ $number }}</a></span>
                @endif
            @endforeach
        </div>
    @endif

    @if(!empty($footer['support_emails']) && is_array($footer['support_emails']))
        <div>
            @foreach($footer['support_emails'] as $email)
                @if($email)
                <span class="inline-block mr-2">📧 <a href="mailto:{{ $email }}" class="underline hover:text-indigo-500">{{ $email }}</a></span>
                @endif
            @endforeach
        </div>
    @endif

    @if(!empty($footer['social']) && is_array($footer['social']))
        <div>
            @foreach($footer['social'] as $link)
                @if($link)
                <a href="{{ $link }}" target="_blank" class="inline-block mr-2 underline hover:text-indigo-500">
                    🔗 {{ parse_url($link, PHP_URL_HOST) ?? $link }}
                </a>
                @endif
            @endforeach
        </div>
    @endif

    @if(!empty($footer['extra']))
        <div class="text-xs text-gray-500 mt-2">{{ $footer['extra'] }}</div>
    @endif

    <div class="text-xs text-gray-400 mt-2">
        &copy; {{ date('Y') }} Your Company. All rights reserved.
    </div>
</footer>
@endif


@endsection
