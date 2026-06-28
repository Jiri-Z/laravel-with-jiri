<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel With Jiri') }} — Terms of Service</title>
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
                        Laravel With <span class="text-indigo-600">Jiri</span>
                    </a>
                    <nav class="flex items-center gap-4">
                        <button onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('dark-mode', document.documentElement.classList.contains('dark'))"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </button>
                        @auth
                            <a href="{{ url('/courses') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">Courses</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-sm">Get Started</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Terms of Service</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Last updated: June 28, 2026</p>

            <div class="mt-10 space-y-8 text-base leading-relaxed">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">1. Acceptance of Terms</h2>
                    <p class="text-gray-600 dark:text-gray-300">By accessing or using Laravel With Jiri ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree, you may not use the Platform.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">2. Description of Service</h2>
                    <p class="text-gray-600 dark:text-gray-300">The Platform provides interactive e-learning content, including coding exercises, quizzes, and tutorials, to help users learn Laravel development. Content is provided for educational purposes only.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">3. User Accounts</h2>
                    <p class="text-gray-600 dark:text-gray-300">You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account. You must provide accurate and complete registration information.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">4. Acceptable Use</h2>
                    <p class="text-gray-600 dark:text-gray-300">You agree not to:</p>
                    <ul class="mt-2 space-y-2 text-gray-600 dark:text-gray-300 list-disc list-inside">
                        <li>Use the Platform for any unlawful purpose</li>
                        <li>Attempt to disrupt or compromise the Platform's security</li>
                        <li>Share your account credentials with others</li>
                        <li>Reproduce, distribute, or create derivative works of Platform content without authorization</li>
                        <li>Use automated tools to access or scrape the Platform</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">5. Intellectual Property</h2>
                    <p class="text-gray-600 dark:text-gray-300">All content on the Platform, including but not limited to text, code examples, quizzes, and design, is owned by or licensed to Laravel With Jiri. You may not reproduce, distribute, or create derivative works without express written permission.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">6. Limitation of Liability</h2>
                    <p class="text-gray-600 dark:text-gray-300">The Platform is provided "as is" without warranties of any kind. Laravel With Jiri shall not be liable for any damages arising from your use of the Platform, including but not limited to direct, indirect, incidental, or consequential damages.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">7. Termination</h2>
                    <p class="text-gray-600 dark:text-gray-300">We reserve the right to suspend or terminate your access to the Platform at our discretion, without prior notice, for violations of these Terms or for any other reason.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">8. Changes to Terms</h2>
                    <p class="text-gray-600 dark:text-gray-300">We may modify these Terms at any time. Changes will be posted on this page with an updated date. Continued use of the Platform after changes constitute acceptance of the new Terms.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">9. Contact</h2>
                    <p class="text-gray-600 dark:text-gray-300">For questions about these Terms, please contact us at support&#64;laravelwithjiri.test.</p>
                </section>
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <svg viewBox="0 0 62 65" class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03707 48.7382 6.99114 48.9132 6.99114C49.0882 6.99114 49.2601 7.03707 49.4118 7.12434L61.39 14.0208C61.4324 14.0457 61.4648 14.0769 61.5022 14.1068C61.5346 14.1317 61.5707 14.1529 61.5994 14.1816C61.6343 14.2165 61.6592 14.2576 61.6891 14.2975C61.7103 14.3274 61.7378 14.3536 61.7565 14.386C61.7839 14.4346 61.8013 14.4882 61.8213 14.5405C61.8312 14.5692 61.8475 14.5954 61.8562 14.6253C61.878 14.7102 61.8897 14.7978 61.8898 14.8858L61.8897 14.8858V14.8858Z"/></svg>
                        <span>Made with Laravel</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('terms') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Terms of Service</a>
                        <a href="{{ route('privacy') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
