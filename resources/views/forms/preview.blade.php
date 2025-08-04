@extends('layouts.app')

@section('content')
<div class="flex justify-center py-10 px-4 bg-gray-100 min-h-screen">
    <div class="w-full max-w-2xl">

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
                <img src="{{ $logo }}" class="w-20 h-20 object-contain mx-auto mb-3" alt="Company Logo Preview" />
            @endif
            @if($headerImage)
                <img src="{{ $headerImage }}" class="mb-4 w-32 h-32 mx-auto object-cover rounded-full border-4 border-indigo-200 shadow" alt="Form Banner Preview"/>
            @endif
            <h2 class="text-3xl font-bold mb-1"
                style="color: {{ $themeColor }}; text-align:{{ $align }}">
                {{ $title }}
            </h2>
            @if($subtitle)
                <span class="text-base text-gray-500">{{ $subtitle }}</span>
            @endif
        </header>

        <div class="bg-white text-gray-900 rounded-2xl shadow-xl p-8 mb-8">
            @if(isset($form->fields) && is_array($form->fields) && count($form->fields))
                <form class="space-y-6" autocomplete="off" onsubmit="return false;">
                    @foreach($form->fields as $field)
                        @php
                            $name = isset($field['name']) && is_string($field['name']) ? $field['name'] : '';
                            $label = isset($field['label']) && is_string($field['label']) ? $field['label'] : ($name ?: 'Field');
                            $type = $field['type'] ?? 'short_answer';
                            $required = isset($field['rules']) && is_string($field['rules']) && str_contains($field['rules'], 'required');
                            $options = (isset($field['options']) && is_array($field['options'])) ? $field['options'] : [];
                        @endphp
                        <div>
                            <label class="block font-semibold mb-2 text-gray-800">
                                {{ $label }}@if($required)<span class="text-red-500">*</span>@endif
                            </label>
                            @switch($type)
                                @case('short_answer')
                                    <input type="text" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                                    @break
                                @case('paragraph')
                                    <textarea disabled rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed"></textarea>
                                    @break
                                @case('email')
                                    <input type="email" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                                    @break
                                @case('mobile')
                                    <input type="tel" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                                    @break
                                @case('single_choice')
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg bg-gray-50 cursor-not-allowed">
                                            <input type="radio" disabled /> <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                    @break
                                @case('multiple_choice')
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg bg-gray-50 cursor-not-allowed">
                                            <input type="checkbox" disabled /> <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                    @break
                                @case('file')
                                    <input type="file" disabled class="w-full bg-gray-100 cursor-not-allowed" />
                                    @break
                                @case('location')
                                    <input type="text" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                                    @break
                                @case('age')
                                    <input type="number" min="0" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                                    @break
                                @default
                                    <input type="text" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" />
                            @endswitch
                        </div>
                    @endforeach
                </form>
            @else
                <p class="text-gray-500">No fields yet! Add some fields in the builder.</p>
            @endif
        </div>

        {{-- FOOTER --}}
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

    </div>
</div>
@endsection
