<div>

    <x-laravel-maker::danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Add Module') }}</x-laravel-maker::danger-button>

    <x-laravel-maker::modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable="true">
        <form wire:submit="save" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <div class="mt-6">
                <x-laravel-maker::label for="txtModelName">
                    Module Name<span class="required">*</span>
                </x-laravel-maker::label>
                <x-laravel-maker::input type="text" wire:model="form.module_name" required placeholder="Module name"/>
                @error("form.module_name") <span>{{ $message }}</span> @enderror
            </div>
            <div class="mt-6">
                <x-laravel-maker::label for="controllerType">Controller Type</x-laravel-maker::label>
                <x-laravel-maker::select wire:model="form.controller_type" :options="$form->controllerTypeOptions"/>
                @error("form.controller_type") <span>{{ $message }}</span> @enderror
            </div>
            <div class="mt-6">
                <x-laravel-maker::label for="controllerName">Controller Name</x-laravel-maker::label>
                <x-laravel-maker::input type="text" wire:model="form.controller_name" placeholder="Controller Name"/>
                @error("form.controller_name") <span>{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <x-laravel-maker::secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-laravel-maker::secondary-button>

                <x-laravel-maker::danger-button class="ms-3">
                    {{ __('Submit') }}
                </x-laravel-maker::danger-button>
            </div>
        </form>
    </x-laravel-maker::modal>
</div>
