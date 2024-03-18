<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <div class="flex">
            <x-laravel-maker::button type="submit" wire:click="submit"> Save</x-laravel-maker::button>
            <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
            <x-laravel-maker::button type="button" wire:click="addFormFieldRow"> Add Field</x-laravel-maker::button>
            <x-laravel-maker::button type="button" wire:click="addFormRelationalFieldRow"> Add RelationShip</x-laravel-maker::button>
            <x-laravel-maker::button type="button"> Add Timestamps</x-laravel-maker::button>
        </div>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
