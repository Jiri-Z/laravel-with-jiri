<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', __('navigation.logo_text')) }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
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
        window.toggleDarkMode = function() {
            var theme = localStorage.getItem('dark-mode');
            if (theme !== 'dark' && theme !== 'light' && theme !== 'auto') { theme = 'auto'; }
            if (theme === 'dark') { theme = 'light'; }
            else if (theme === 'light') { theme = 'auto'; }
            else { theme = 'dark'; }
            localStorage.setItem('dark-mode', theme);
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme:dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        };
    </script>
</head>
<body class="font-sans antialiased text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-950">
    <div class="min-h-screen">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 opacity-70 dark:opacity-10"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gradient-to-br from-indigo-100/60 to-purple-100/60 rounded-full blur-3xl -top-96 dark:from-indigo-900/20 dark:to-purple-900/20"></div>

            <header class="relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <a href="/" class="inline-flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            <x-application-logo class="w-7 h-7 text-indigo-600" />
                            {{ __('navigation.logo_text') }}
                        </a>
                        <nav class="flex items-center gap-4">
                            <button onclick="toggleDarkMode()"
                                class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800"
                                aria-label="{{ __('navigation.dark_mode') }}">
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </button>
                            @auth
                                <a href="{{ url('/courses') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">{{ __('landing.nav_courses') }}</a>
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">{{ __('landing.nav_dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">{{ __('landing.nav_login') }}</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 dark:bg-indigo-500 rounded-full hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all shadow-sm">{{ __('landing.nav_get_started') }}</a>
                            @endauth
                        </nav>
                    </div>
                </div>
            </header>

            <section class="relative z-10 pt-20 pb-24 sm:pt-28 sm:pb-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                        <div class="max-w-xl animate-fade-in-up">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">{{ __('landing.hero_badge') }}</span>
                            <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                                {{ __('landing.hero_heading_1') }}
                                <span class="text-indigo-600">{{ __('landing.hero_heading_2') }}</span>
                            </h1>
                            <p class="mt-5 text-lg sm:text-xl text-gray-600 leading-relaxed">
                                {{ __('landing.hero_description') }}
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-indigo-600 dark:bg-indigo-500 rounded-full hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all shadow-md hover:shadow-lg">
                                    {{ __('landing.hero_cta_start') }}
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="#courses" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-600 rounded-full hover:border-gray-300 dark:hover:border-gray-500 hover:text-gray-900 dark:hover:text-white transition-all">
                                    {{ __('landing.hero_cta_browse') }}
                                </a>
                            </div>
                        </div>
                        <div class="hidden lg:flex justify-center animate-fade-in-up" style="animation-delay: 0.2s">
                            <div class="relative w-80 h-80">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-3xl rotate-6 opacity-20"></div>
                                <div class="absolute inset-0 bg-white rounded-3xl shadow-xl border border-gray-100 p-8 -rotate-3">
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ __('landing.feature_coding_icon') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('landing.feature_coding_description') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ __('landing.feature_pace_icon') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('landing.feature_pace_description') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ __('landing.feature_tracking_icon') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('landing.feature_tracking_description') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-100 rounded-2xl -rotate-6 flex items-center justify-center shadow-lg">
                                    <span class="text-2xl font-bold text-indigo-600">{{ __('landing.feature_tracking_stat') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="relative -mt-8 h-32 bg-gradient-to-b from-transparent to-white dark:to-gray-950"></div>
        </div>

        <section id="courses" class="py-20 sm:py-28 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">{{ __('landing.courses_badge') }}</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">{{ __('landing.courses_heading') }}</h2>
                    <p class="mt-4 text-lg text-gray-600">{{ __('landing.courses_description') }}</p>
                </div>

                @if ($courses->isEmpty())
                    <div class="mt-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="text-gray-500 text-lg">{{ __('landing.courses_empty') }}</p>
                    </div>
                @else
                    <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($courses as $course)
                            <a href="{{ route('courses.show', $course) }}" class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-600 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-700 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                                    @if (str_starts_with($course->slug, 'cs-'))
                                        <span class="inline-block mr-1" title="{{ __('courses.czech_course') }}">🇨🇿</span>
                                    @endif
                                    {{ $course->title }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-2">{{ $course->description }}</p>
                                <div class="mt-4 flex items-center gap-4 text-xs text-gray-400 dark:text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        {{ trans_choice('landing.lesson_count', $course->lessons_count) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="py-20 sm:py-28 bg-white dark:bg-gray-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">{{ __('landing.features_badge') }}</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">{{ __('landing.features_heading') }}</h2>
                </div>
                <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('landing.feature_coding_title') }}</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ __('landing.feature_coding_description') }}</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('landing.feature_pace_title') }}</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ __('landing.feature_pace_description') }}</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('landing.feature_tracking_title') }}</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ __('landing.feature_tracking_description') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 sm:py-28 bg-gradient-to-br from-indigo-600 to-indigo-700">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">{{ __('landing.cta_heading') }}</h2>
                <p class="mt-4 text-lg text-indigo-100">{{ __('landing.cta_description') }}</p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 text-base font-semibold text-indigo-700 bg-white rounded-full hover:bg-indigo-50 transition-all shadow-lg">
                        {{ __('landing.cta_button') }}
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" viewBox="0 0 62 65" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z"/></svg>
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

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out both;
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up {
                animation: none;
            }
        }
    </style>
</body>
</html>
