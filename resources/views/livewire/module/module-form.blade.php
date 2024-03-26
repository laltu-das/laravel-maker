<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form wire:submit="login">
            <div class="">
                <div class="grid gap-4 mb-4 sm:grid-cols-5">
                    <div class="form-group col-md-4">
                        <x-laravel-maker::label for="txtModelName">
                            Model Name<span class="required">*</span>
                        </x-laravel-maker::label>
                        <x-laravel-maker::input type="text" wire:model="form.module_name" required placeholder="Module name"/>
                    </div>
                    <div class="form-group col-md-4">
                        <x-laravel-maker::label for="controllerType">Controller Type</x-laravel-maker::label>
                        <x-laravel-maker::select wire:model="form.controller_type" :options="$form->controllerTypeOptions"/>
                    </div>
                    <div class="form-group col-md-4">
                        <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                        <x-laravel-maker::input type="text" wire:model="form.controller_name" placeholder="Controller Name"/>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <x-laravel-maker::button type="submit" wire:click="save"> Submit</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
            </div>
        </form>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
