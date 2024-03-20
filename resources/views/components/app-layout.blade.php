@php use Laltu\LaravelMaker\Facades\LaravelMaker; @endphp
        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>Laravel Maker</title>

    <!-- Fonts -->
{{--    <link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet"/>--}}

    {!! LaravelMaker::css() !!}

    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="bg-gray-50 dark:bg-gray-950 min-h-screen">
    <div class="flex bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white" x-data="{ navActiveTab: 'tab1' }">
        <aside class="flex h-screen w-20 flex-col items-center border-r border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white">
            <div class="flex h-[4.5rem] w-full items-center justify-center border-b border-gray-200 dark:border-gray-600 p-2">
                <x-laravel-maker::icons.application/>
            </div>
            <nav class="flex flex-1 flex-col gap-y-4 pt-10">
                <a href="{{ route('dashboard') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab1', 'text-white': navActiveTab === 'tab1' }"
                   class="cursor-pointer group relative rounded-xl bg-gray-100 p-2 text-blue-600 hover:bg-gray-50">
                    <svg class="h-6 w-6 stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 4H6C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V6C20 4.89543 19.1046 4 18 4Z"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 9V15M9 12H15H9Z" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="{{ route('schema') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab2', 'text-white': navActiveTab === 'tab2' }"
                   class="cursor-pointer text-gary-400 group relative rounded-xl p-2 hover:bg-gray-50">
                    <x-laravel-maker::icons.menu-setting/>
                </a>
                <a href="{{ route('module') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab3', 'text-white': navActiveTab === 'tab3' }"
                   class="cursor-pointer text-gary-400 group relative rounded-xl p-2 hover:bg-gray-50">
                    <x-laravel-maker::icons.menu-setting/>
                </a>
                <a href="{{ route('setting') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab3', 'text-white': navActiveTab === 'tab3' }"
                   class="cursor-pointer text-gary-400 group relative rounded-xl p-2 hover:bg-gray-50">
                    <x-laravel-maker::icons.menu-setting/>
                </a>
                <a href="{{ route('generator') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab3', 'text-white': navActiveTab === 'tab3' }"
                   class="cursor-pointer text-gary-400 group relative rounded-xl p-2 hover:bg-gray-50">
                    <x-laravel-maker::icons.menu-setting/>
                </a>
            </nav>
            <div class="flex flex-col items-center gap-y-4 py-10">
                <button class="group relative rounded-xl p-2 text-gray-400 hover:bg-gray-100">
                    <svg width="24" height="24" class="h-6 w-6 stroke-current" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 16H12.01M12 8V12V8Z" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>

                <button class="mt-2 rounded-full bg-gray-100">
{{--                    <img class="h-10 w-10 rounded-full" src="https://avatars.githubusercontent.com/u/35387401?v=4"--}}
{{--                         alt=""/>--}}
                </button>
            </div>
        </aside>
        <main class="w-full">
            <header class="px-5 bg-white dark:bg-gray-950 border-b border-gray-200">
                <div class="py-3 sm:py-5 mx-auto dark:border-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="ml-2 text-lg sm:text-2xl text-gray-700 dark:text-gray-300 font-medium"><b
                                        class="font-bold">Laravel</b> Maker</span>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-6">
                            <x-laravel-maker::theme-switcher/>
                        </div>
                    </div>
                </div>
            </header>
            <section class="pt-6 px-6 pb-12 w-full">
                {{ $slot }}
            </section>
        </main>
    </div>
</div>
@livewireScripts
{!! LaravelMaker::js() !!}
</body>
</html>