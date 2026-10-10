<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Convedya') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#070b18] text-white antialiased">

    {{-- Navigation --}}
    <header class="border-b border-white/10 bg-[#080d1c]/90 backdrop-blur-xl">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="text-3xl font-bold tracking-tight">
                <span class="text-white">con</span><span class="text-violet-400">vedya</span>
            </a>

            {{-- Navigation --}}
            <div class="flex items-center gap-2 sm:gap-4">

                <a
                    href="#features"
                    class="hidden rounded-lg px-3 py-2 text-sm text-gray-400 transition hover:bg-white/5 hover:text-white sm:block"
                >
                    Features
                </a>

                <a
                    href="#how-it-works"
                    class="hidden rounded-lg px-3 py-2 text-sm text-gray-400 transition hover:bg-white/5 hover:text-white sm:block"
                >
                    How it works
                </a>

                @if (Route::has('login'))
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-lg border border-white/10 px-4 py-2 text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-300 transition hover:text-white"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 transition hover:bg-gray-200"
                            >
                                Get started
                            </a>
                        @endif
                    @endauth
                @endif

            </div>
        </nav>
    </header>


    {{-- Page content --}}
    <main>
        {{ $slot ?? '' }}

        @yield('content')
    </main>


    {{-- Footer --}}
    <footer class="border-t border-white/10 bg-[#070b18]">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-6 py-8 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <div>
                <span class="font-semibold text-gray-300">con<span class="text-violet-400">vedya</span></span>
                <span class="ml-2">
                    Learn through conversation.
                </span>
            </div>

            <div class="flex gap-5">
                <a href="#" class="transition hover:text-gray-300">
                    About
                </a>

                <a href="#" class="transition hover:text-gray-300">
                    Privacy
                </a>

                <a href="#" class="transition hover:text-gray-300">
                    Terms
                </a>
            </div>

            <p>
                © {{ date('Y') }} Convedya
            </p>

        </div>
    </footer>

</body>
</html>


