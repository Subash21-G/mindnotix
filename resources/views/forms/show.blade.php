@extends('layouts.app')

@section('content')
<div class="flex justify-center py-10 px-4 bg-gray-100 min-h-screen">
    <div class="w-full max-w-2xl">

        {{-- HEADER SECTION --}}
        @php
            use Illuminate\Support\Str;
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
                <img src="{{ Str::startsWith($logo, ['http://', 'https://', 'data:']) ? $logo : asset('storage/' . ltrim($logo, '/')) }}"
                    class="w-20 h-20 object-contain mx-auto mb-3" alt="Company Logo" />
            @endif
            @if($headerImage)
                <img src="{{ Str::startsWith($headerImage, ['http://', 'https://', 'data:']) ? $headerImage : asset('storage/' . ltrim($headerImage, '/')) }}"
                    class="mb-4 w-32 h-32 mx-auto object-cover rounded-full border-4 border-indigo-200 shadow"
                    alt="Form Banner" />
            @endif
            <h2 class="text-3xl font-bold mb-1"
                style="color: {{ $themeColor }}; text-align:{{ $align }}">
                {{ $title }}
            </h2>
            @if($subtitle)
                <span class="text-base text-gray-500">{{ $subtitle }}</span>
            @endif
        </header>

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM BODY --}}
        <div class="bg-white text-gray-900 rounded-2xl shadow-xl p-8">
            <form action="{{ route('responses.store', $form) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($form->fields) && is_array($form->fields) && count($form->fields))
                    @foreach($form->fields as $field)
                        @php
                            $name     = isset($field['name']) && is_string($field['name']) ? $field['name'] : '';
                            $label    = isset($field['label']) && is_string($field['label']) ? $field['label'] : ($name ?: 'Field');
                            $type     = $field['type'] ?? 'short_answer';
                            $required = isset($field['rules']) && is_string($field['rules']) && str_contains($field['rules'], 'required');
                            $options  = (isset($field['options']) && is_array($field['options'])) ? $field['options'] : [];
                        @endphp
                        <div>
                            <label for="{{ $name }}" class="block font-semibold mb-2 text-gray-800">
                                {{ $label }}@if($required)<span class="text-red-500">*</span>@endif
                            </label>
                            @switch($type)
                                @case('short_answer')
                                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                                    @break
                                @case('paragraph')
                                    <textarea name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        rows="4"
                                        @if($required) required @endif>{{ old($name) }}</textarea>
                                    @break
                                @case('email')
                                    <input type="email" name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                                    @break
                                @case('mobile')
                                    <input type="tel" name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                                    @break
                                @case('single_choice')
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg hover:bg-gray-50">
                                            <input type="radio" name="{{ $name }}" value="{{ $option }}"
                                                @if(old($name) == $option) checked @endif
                                                @if($required) required @endif>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                    @break
                                @case('multiple_choice')
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg hover:bg-gray-50">
                                            <input type="checkbox" name="{{ $name }}[]" value="{{ $option }}"
                                                @if(is_array(old($name)) && in_array($option, old($name))) checked @endif>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                    @break
                                @case('file')
                                    <input type="file" name="{{ $name }}" id="{{ $name }}" class="w-full"
                                        @if($required) required @endif>
                                    @break
                                @case('location')
                                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                                    @break
                                @case('age')
                                    <input type="number" name="{{ $name }}" id="{{ $name }}"
                                        min="0"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                                    @break
                                @default
                                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="{{ old($name) }}"
                                        @if($required) required @endif>
                            @endswitch
                            @error($name)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                @else
                    <div class="text-gray-400">No fields defined for this form.</div>
                @endif

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-lg">
                    🚀 Submit Response
                </button>
            </form>
        </div>

        {{-- FOOTER (dynamic) --}}
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
