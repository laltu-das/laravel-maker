@php use Laltu\LaravelMaker\Facades\LaravelMaker; @endphp
        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>Laravel Maker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet"/>

    {!! LaravelMaker::css() !!}

    @livewireStyles

    {!! LaravelMaker::js() !!}
</head>
<body class="font-sans antialiased">
<div class="bg-gray-50 dark:bg-gray-950 min-h-screen">
    <div class="flex bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white" x-data="{ navActiveTab: 'tab1' }">
        <aside class="flex h-auto w-20 flex-col items-center border-r border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white">
            <div class="flex h-[4.5rem] w-full items-center justify-center border-b border-gray-200 dark:border-gray-600 p-2">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThsapwuIZ2JPUVRaWSoX_xoEIOHWxneY7EupS8gsFriA&s"/>
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
                    <svg width="24" height="24" class="h-6 w-6 stroke-current group-hover:text-blue-600"
                         viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 10.8181 3.23279 9.64778 3.68508 8.55585C4.13738 7.46392 4.80031 6.47177 5.63604 5.63604C6.47177 4.80031 7.46392 4.13738 8.55585 3.68508C9.64778 3.23279 10.8181 3 12 3C14.3869 3 16.6761 3.84285 18.364 5.34315C20.0518 6.84344 21 8.87827 21 11C21 12.0609 20.5259 13.0783 19.682 13.8284C18.8381 14.5786 17.6935 15 16.5 15H14C13.5539 14.9928 13.1181 15.135 12.7621 15.404C12.4061 15.673 12.1503 16.0533 12.0353 16.4844C11.9203 16.9155 11.9528 17.3727 12.1276 17.7833C12.3025 18.1938 12.6095 18.5341 13 18.75C13.1997 18.9342 13.3366 19.1764 13.3915 19.4425C13.4465 19.7085 13.4167 19.9851 13.3064 20.2334C13.196 20.4816 13.0107 20.6891 12.7764 20.8266C12.5421 20.9641 12.2705 21.0247 12 21"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.5 11C7.77614 11 8 10.7761 8 10.5C8 10.2239 7.77614 10 7.5 10C7.22386 10 7 10.2239 7 10.5C7 10.7761 7.22386 11 7.5 11Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 8C12.2761 8 12.5 7.77614 12.5 7.5C12.5 7.22386 12.2761 7 12 7C11.7239 7 11.5 7.22386 11.5 7.5C11.5 7.77614 11.7239 8 12 8Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.5 11C16.7761 11 17 10.7761 17 10.5C17 10.2239 16.7761 10 16.5 10C16.2239 10 16 10.2239 16 10.5C16 10.7761 16.2239 11 16.5 11Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="{{ route('module') }}" wire:navigate
                   :class="{ 'bg-blue-500': navActiveTab === 'tab3', 'text-white': navActiveTab === 'tab3' }"
                   class="cursor-pointer text-gary-400 group relative rounded-xl p-2 hover:bg-gray-50">
                    <svg width="24" height="24" class="h-6 w-6 stroke-current group-hover:text-blue-600"
                         viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 10.8181 3.23279 9.64778 3.68508 8.55585C4.13738 7.46392 4.80031 6.47177 5.63604 5.63604C6.47177 4.80031 7.46392 4.13738 8.55585 3.68508C9.64778 3.23279 10.8181 3 12 3C14.3869 3 16.6761 3.84285 18.364 5.34315C20.0518 6.84344 21 8.87827 21 11C21 12.0609 20.5259 13.0783 19.682 13.8284C18.8381 14.5786 17.6935 15 16.5 15H14C13.5539 14.9928 13.1181 15.135 12.7621 15.404C12.4061 15.673 12.1503 16.0533 12.0353 16.4844C11.9203 16.9155 11.9528 17.3727 12.1276 17.7833C12.3025 18.1938 12.6095 18.5341 13 18.75C13.1997 18.9342 13.3366 19.1764 13.3915 19.4425C13.4465 19.7085 13.4167 19.9851 13.3064 20.2334C13.196 20.4816 13.0107 20.6891 12.7764 20.8266C12.5421 20.9641 12.2705 21.0247 12 21"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.5 11C7.77614 11 8 10.7761 8 10.5C8 10.2239 7.77614 10 7.5 10C7.22386 10 7 10.2239 7 10.5C7 10.7761 7.22386 11 7.5 11Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 8C12.2761 8 12.5 7.77614 12.5 7.5C12.5 7.22386 12.2761 7 12 7C11.7239 7 11.5 7.22386 11.5 7.5C11.5 7.77614 11.7239 8 12 8Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.5 11C16.7761 11 17 10.7761 17 10.5C17 10.2239 16.7761 10 16.5 10C16.2239 10 16 10.2239 16 10.5C16 10.7761 16.2239 11 16.5 11Z"
                              fill="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
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
                    <img class="h-10 w-10 rounded-full" src="https://avatars.githubusercontent.com/u/35387401?v=4"
                         alt=""/>
                </button>
            </div>
        </aside>
        <main class="pt-6 px-6 pb-12 w-full">
            <header class="px-5">
                <div class="py-3 sm:py-5 mx-auto border-b border-gray-200 dark:border-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg width="33" height="32" viewBox="0 0 33 32" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M15.4566 6.75005C15.9683 6.75596 16.4047 7.09621 16.5001 7.56364L18.8747 19.2038L19.999 17.1682C20.1832 16.8347 20.5526 16.625 20.9559 16.625H31.1744C31.7684 16.625 32.25 17.0727 32.25 17.625C32.25 18.1773 31.7684 18.625 31.1744 18.625H21.6127L19.3581 22.7068C19.1483 23.0867 18.7021 23.3008 18.2475 23.2397C17.7928 23.1786 17.4301 22.8559 17.3445 22.4363L15.376 12.7868L13.1334 22.4607C13.0282 22.9146 12.6007 23.2414 12.1013 23.2498C11.6019 23.2582 11.162 22.9458 11.0393 22.4957L9.30552 16.1378L8.19223 18.0929C8.00581 18.4202 7.64002 18.625 7.2416 18.625H1.32563C0.731576 18.625 0.25 18.1773 0.25 17.625C0.25 17.0727 0.731576 16.625 1.32563 16.625H6.59398L8.71114 12.9071C8.9193 12.5415 9.34805 12.3328 9.78979 12.3821C10.2315 12.4313 10.5951 12.7283 10.7044 13.1292L11.9971 17.8695L14.3918 7.53929C14.4996 7.07421 14.9449 6.74414 15.4566 6.75005Z"
                                      fill="url(#paint0_linear_4_31)"/>
                                <defs>
                                    <linearGradient id="paint0_linear_4_31" x1="16.25" y1="6.74997" x2="16.25"
                                                    y2="23.25" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F85A5A"/>
                                        <stop offset="0.828125" stop-color="#7A5AF8"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span class="ml-2 text-lg sm:text-2xl text-gray-700 dark:text-gray-300 font-medium"><b
                                        class="font-bold">Laravel</b> Maker</span>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-6">
                            <x-laravel-maker::theme-switcher/>
                        </div>
                    </div>
                </div>
            </header>
            <div class="mx-auto grid default:grid-cols-12 default:gap-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>