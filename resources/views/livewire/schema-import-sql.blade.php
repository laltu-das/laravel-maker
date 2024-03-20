<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form wire:submit="submit">
            <div class="flex">
                <x-laravel-maker::button type="submit" wire:click="save"> Save</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::textarea wire:model="sqlData" placeholder="Raw Sql data" required=""/>
                @error('sqlData')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </form>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>