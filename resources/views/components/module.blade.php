@props(['module'])
<div class="bg-white">
    <nav class="flex flex-col sm:flex-row">
        <a wire:navigate href="{{ route('module-view',$module->id) }}"
           class="text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none {{ request()->routeIs('module-view')?'border-b-2 font-medium border-blue-500':'' }}"
           x-data="{ active: false, u() { this.active = (window.location.href.includes('module-view') ) }}"
           x-init="u()"
           x-on:alpine:navigated.document="$nextTick(u)"
           x-bind:class="active && 'border-b-2 font-medium border-blue-500'">
            Modules
        </a>
        <a wire:navigate href="{{ route('module-form',$module->id) }}"
           class="text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none {{ request()->routeIs('module-form')?'border-b-2 font-medium border-blue-500':'' }}"
           x-data="{ active: false, u() { this.active = (window.location.href.includes('form') ) }}"
           x-init="u()"
           x-on:alpine:navigated.document="$nextTick(u)"
           x-bind:class="active && 'border-b-2 font-medium border-blue-500'">
            Form Builder
        </a>
        <a wire:navigate href="{{ route('module-api',$module->id) }}"
           class="text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none {{ request()->routeIs('module-api')?'border-b-2 font-medium border-blue-500':'' }}"
           x-data="{ active: false, u() { this.active = (window.location.href.includes('api') ) }}"
           x-init="u()"
           x-on:alpine:navigated.document="$nextTick(u)"
           x-bind:class="active && 'border-b-2 font-medium border-blue-500'">
            Api Setting
        </a>
    </nav>
</div>