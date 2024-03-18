<div>
    <x-laravel-maker::card>
        <x-laravel-maker::card-body>
            <form wire:submit="login">
                <div class="">
                    <div class="grid gap-4 mb-4 sm:grid-cols-5">
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="txtModelName">
                                Model Name<span class="required">*</span>
                            </x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="moduleName" required placeholder="Module name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerType">Controller Type</x-laravel-maker::label>
                            <x-laravel-maker::select wire:model="controllerType">
                                <option value="api_scaffold">API Scaffold Generator</option>
                                <option value="api">API Generator</option>
                                <option value="scaffold">Scaffold Generator</option>
                            </x-laravel-maker::select>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
                        </div>
                        <div class="form-group col-md-4">
                            <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                            <x-laravel-maker::input type="text" wire:model="controllerName" placeholder="Controller Name"/>
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
</div>