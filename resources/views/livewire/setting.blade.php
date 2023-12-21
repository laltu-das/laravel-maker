<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form id="form">
            <div class="flex">
                <x-laravel-maker::button type="submit" wire:click="submit"> Save</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFormFieldRow"> Add Field</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFormRelationalFieldRow"> Add RelationShip</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Add Timestamps</x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::input type="text" wire:model="table_name" placeholder="Model Name" required="true"/>
            </div>
        </form>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
