<div class="mx-auto">
    <div class="flex gap-2">
        @livewire(\Laltu\LaravelMaker\Livewire\Module\ModuleCreate::class)
    </div>
    <x-laravel-maker::card>
        <x-laravel-maker::card-header>
            <h1 class="box-title">Laravel Generator Builder</h1>
        </x-laravel-maker::card-header>
        <x-laravel-maker::card-body>
            <x-laravel-maker::table class="table table-striped table-bordered">
                <x-laravel-maker::thead class="no-border">
                    <x-laravel-maker::tr>
                        <x-laravel-maker::th>Module Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Model Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Controller Type</x-laravel-maker::th>
                        <x-laravel-maker::th>Controller Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Controller</x-laravel-maker::th>
                        <x-laravel-maker::th>Request</x-laravel-maker::th>
                        <x-laravel-maker::th>Response</x-laravel-maker::th>
                        <x-laravel-maker::th>Service</x-laravel-maker::th>
                        <x-laravel-maker::th>Action</x-laravel-maker::th>
                        <x-laravel-maker::th>View</x-laravel-maker::th>
                        <x-laravel-maker::th>Route</x-laravel-maker::th>
                        <x-laravel-maker::th>Action</x-laravel-maker::th>
                    </x-laravel-maker::tr>
                </x-laravel-maker::thead>
                <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                    @foreach ($modules as $module)
                        <x-laravel-maker::tr>
                            <x-laravel-maker::td>
                                {{ $module->module_name }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                {{ $module->model_name }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                {{ $module->controller_type }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                {{ $module->controller_name }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateController({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateRequest({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateResponse({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateService({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateAction({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateView({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="generateRoute({{ $module->id }})" wire:loading.class="animate-spin"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td class="flex">
                                <x-laravel-maker::icon-link :href="route('laravel-maker.module.edit', $module->id)">
                                    <x-laravel-maker::icons.cog class="m-auto cursor-pointer"/>
                                </x-laravel-maker::icon-link>
                                <x-laravel-maker::icon-link :href="route('laravel-maker.module.form', $module->id)">
                                    <x-laravel-maker::icons.cog class="m-auto cursor-pointer"/>
                                </x-laravel-maker::icon-link>
                                <x-laravel-maker::icon-link :href="route('laravel-maker.module.api', $module->id)">
                                    <x-laravel-maker::icons.cog class="m-auto cursor-pointer"/>
                                </x-laravel-maker::icon-link>
                            </x-laravel-maker::td>
                        </x-laravel-maker::tr>
                    @endforeach
                </x-laravel-maker::tbody>
            </x-laravel-maker::table>
        </x-laravel-maker::card-body>
    </x-laravel-maker::card>
</div>