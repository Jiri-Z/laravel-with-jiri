<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', __('navigation.logo_text')) }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            var theme = localStorage.getItem('dark-mode');
            if (theme !== 'dark' && theme !== 'light' && theme !== 'auto') {
                theme = 'auto';
                localStorage.setItem('dark-mode', 'auto');
            }
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="font-sans antialiased text-gray-600 bg-gray-50 dark:bg-gray-950">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md px-4 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <a href="/" class="inline-flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                    <x-application-logo class="w-7 h-7 text-indigo-600 dark:text-indigo-300" />
                    <span>{{ __('navigation.logo_text') }}</span>
                </a>
                <button @click="(function(){
                    var theme=localStorage.getItem('dark-mode');
                    if(theme!=='dark'&&theme!=='light'&&theme!=='auto'){theme='auto';}
                    if(theme==='dark'){theme='light';}
                    else if(theme==='light'){theme='auto';}
                    else{theme='dark';}
                    localStorage.setItem('dark-mode',theme);
                    if(theme==='dark'||(theme==='auto'&&window.matchMedia('(prefers-color-scheme:dark)').matches)){
                        document.documentElement.classList.add('dark');
                    }else{
                        document.documentElement.classList.remove('dark');
                    }
                })()"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800" x-data
                    aria-label="{{ __('navigation.dark_mode') }}">
                    <svg x-show="!document.documentElement.classList.contains('dark')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="document.documentElement.classList.contains('dark')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 px-6 py-4 sm:px-8 sm:py-6">
                {{ $slot }}
            </div>
        </div>
        <p class="mt-6 text-xs text-gray-400 dark:text-gray-500">
            &copy; {{ date('Y') }} {{ __('navigation.logo_text') }}
        </p>
    </div>
</body>
</html>
