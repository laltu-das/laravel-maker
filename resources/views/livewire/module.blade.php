<div class="mx-auto">
    <div class="flex gap-2">
        <x-laravel-maker::button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Add Module') }}
        </x-laravel-maker::button>
    </div>
    <div class="grid gap-4 mb-4 grid-cols-12 justify-between">
        <x-laravel-maker::card class="col-span-4">
            <x-laravel-maker::card-header>
                <h1 class="box-title">Laravel Generator Builder</h1>
            </x-laravel-maker::card-header>
            <x-laravel-maker::card-body>
                <x-laravel-maker::table class="table table-striped table-bordered">
                    <x-laravel-maker::thead class="no-border">
                        <x-laravel-maker::tr>
                            <x-laravel-maker::th>Module Name</x-laravel-maker::th>
                            <x-laravel-maker::th>Action</x-laravel-maker::th>
                        </x-laravel-maker::tr>
                    </x-laravel-maker::thead>
                    <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                        @foreach ($modules as $index => $row)
                            <x-laravel-maker::tr>
                                <x-laravel-maker::td>
                                    {{ $index }}
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    {{ $index }}
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    {{ $index }}
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    {{ $index }}
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    {{ $index }}
                                </x-laravel-maker::td>
                            </x-laravel-maker::tr>
                        @endforeach
                    </x-laravel-maker::tbody>
                </x-laravel-maker::table>
            </x-laravel-maker::card-body>
        </x-laravel-maker::card>
        <x-laravel-maker::modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable="tr">
            <form wire:submit.prevent="submit" class="p-6">

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <x-laravel-maker::label for="password" value="{{ __('Module Name') }}" class="sr-only" />

                    <x-laravel-maker::input
                            wire:model="module_name"
                            id="module_name"
                            name="module_name"
                            type="text"
                            class="mt-1 block w-3/4"
                            placeholder="{{ __('Module Name') }}"
                    />

                    <x-laravel-maker::input-error :messages="$errors->get('module_name')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-laravel-maker::button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-laravel-maker::button>

                    <x-laravel-maker::button type="submit" class="ms-3">
                        {{ __('Delete Account') }}
                    </x-laravel-maker::button>
                </div>
            </form>
        </x-laravel-maker::modal>
    </div>
</div>