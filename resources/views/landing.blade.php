<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jiri — Learn Laravel. By Doing.</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-600 bg-white">
    <div class="min-h-screen">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 opacity-70"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gradient-to-br from-indigo-100/60 to-purple-100/60 rounded-full blur-3xl -top-96"></div>

            <header class="relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <a href="/" class="text-xl font-bold text-gray-900 tracking-tight">
                            jiri<span class="text-indigo-600">.</span>
                        </a>
                        <nav class="flex items-center gap-6">
                            @auth
                                <a href="{{ url('/courses') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Courses</a>
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Log in</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-sm">Get Started</a>
                            @endauth
                        </nav>
                    </div>
                </div>
            </header>

            <section class="relative z-10 pt-20 pb-24 sm:pt-28 sm:pb-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                        <div class="max-w-xl animate-fade-in-up">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">Interactive Laravel Tutorials</span>
                            <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                                Learn Laravel.
                                <span class="text-indigo-600">By Doing.</span>
                            </h1>
                            <p class="mt-5 text-lg sm:text-xl text-gray-600 leading-relaxed">
                                An interactive e-learning platform that takes you from zero to Laravel — through live coding, quizzes, and real-world projects. No videos. No fluff.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                                    Start Learning Free
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="#courses" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-full hover:border-gray-300 hover:text-gray-900 transition-all">
                                    Browse Courses
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
                                                <p class="text-sm font-semibold text-gray-900">Interactive Coding</p>
                                                <p class="text-xs text-gray-500">Write real PHP in your browser</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Quiz-Based Learning</p>
                                                <p class="text-xs text-gray-500">Test your knowledge as you go</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Track Progress</p>
                                                <p class="text-xs text-gray-500">Every step counts toward mastery</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-100 rounded-2xl -rotate-6 flex items-center justify-center shadow-lg">
                                    <span class="text-2xl font-bold text-indigo-600">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="relative -mt-8 h-32 bg-gradient-to-b from-transparent to-white"></div>
        </div>

        <section id="courses" class="py-20 sm:py-28 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">Courses</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Start your journey</h2>
                    <p class="mt-4 text-lg text-gray-600">Choose a course and begin building real Laravel skills — one interactive step at a time.</p>
                </div>

                @if ($courses->isEmpty())
                    <div class="mt-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="text-gray-500 text-lg">No courses published yet. Check back soon!</p>
                    </div>
                @else
                    <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($courses as $course)
                            <a href="{{ route('courses.show', $course) }}" class="group block bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $course->title }}</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $course->description }}</p>
                                <div class="mt-4 flex items-center gap-4 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="py-20 sm:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">Features</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Everything you need to learn Laravel</h2>
                </div>
                <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Interactive Code Editor</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">Write and run real PHP code directly in your browser using the built-in Monaco editor with PHP WASM. No setup required.</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Learn at Your Own Pace</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">Bite-sized lessons with clear explanations and practical examples. Progress at whatever speed works for you.</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Track Your Progress</h3>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">Every step, quiz, and coding exercise tracks your completion automatically. See exactly how far you've come.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 sm:py-28 bg-gradient-to-br from-indigo-600 to-indigo-700">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">Ready to start?</h2>
                <p class="mt-4 text-lg text-indigo-100">Join free and start building real Laravel skills today. No credit card required.</p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 text-base font-semibold text-indigo-700 bg-white rounded-full hover:bg-indigo-50 transition-all shadow-lg">
                        Create Free Account
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} jiri. Built with Laravel, Livewire &amp; Tailwind.
                    </p>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Register</a>
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
