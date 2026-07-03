<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', __('navigation.logo_text')) }} — {{ __('legal.privacy_title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-950">
    <div class="min-h-screen">
        <header class="bg-white dark:bg-gray-950 border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="/" class="inline-flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                        <x-application-logo class="w-7 h-7 text-indigo-600" />
                        {{ __('navigation.logo_text') }}
                    </a>
                    <nav class="flex items-center gap-4">
                        <button onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('dark-mode', document.documentElement.classList.contains('dark'))"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </button>
                        @auth
                            <a href="{{ url('/courses') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">{{ __('landing.nav_courses') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">{{ __('landing.nav_login') }}</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-sm">{{ __('landing.nav_get_started') }}</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">{{ __('legal.privacy_title') }}</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('legal.privacy_updated') }}</p>

            <div class="mt-10 space-y-8 text-base leading-relaxed">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_1_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_1_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_2_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_2_intro') }}</p>
                    <ul class="mt-2 space-y-2 text-gray-600 dark:text-gray-300 list-disc list-inside">
                        <li>{{ __('legal.privacy_section_2_item_1') }}</li>
                        <li>{{ __('legal.privacy_section_2_item_2') }}</li>
                        <li>{{ __('legal.privacy_section_2_item_3') }}</li>
                        <li>{{ __('legal.privacy_section_2_item_4') }}</li>
                        <li>{{ __('legal.privacy_section_2_item_5') }}</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_3_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_3_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_4_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_4_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_5_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_5_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_6_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_6_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_7_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_7_body') }}</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('legal.privacy_section_8_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('legal.privacy_section_8_body') }}</p>
                </section>
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" viewBox="0 0 62 65" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987..."/></svg>
                        <span>{{ __('landing.footer_made_with') }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('terms') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">{{ __('landing.footer_terms') }}</a>
                        <a href="{{ route('privacy') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">{{ __('landing.footer_privacy') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
