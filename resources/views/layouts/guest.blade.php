<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ App\Models\Setting::get('app_name', 'SPK Bansos') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>body { font-family: 'Inter', sans-serif; }</style>
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

            {{-- Logo --}}
            <div class="mb-8">
                <a href="/" class="flex items-center gap-2.5">
                    @if(App\Models\Setting::get('app_logo'))
                        <img src="{{ Storage::url(App\Models\Setting::get('app_logo')) }}" alt="Logo" class="w-10 h-10 rounded-lg object-contain">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                            </svg>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-gray-900">{{ App\Models\Setting::get('app_name', 'SPK Bansos') }}</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="w-full sm:max-w-md bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400">&copy; {{ date('Y') }} {{ App\Models\Setting::get('app_name', 'SPK Bansos') }}</p>
        </div>
    </body>
</html>
