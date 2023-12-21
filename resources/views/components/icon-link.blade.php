@props(['href'])

<a href="{{ $href }}" {{ $attributes }} wire:navigate>
    {{ $slot }}
</a>