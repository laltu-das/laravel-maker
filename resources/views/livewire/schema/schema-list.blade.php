<div class="mx-auto">
    <div class="flex gap-2">
        <x-laravel-maker::link :href="route('laravel-maker.schema.create')"> {{ __('Add Schema') }} </x-laravel-maker::link>
        <x-laravel-maker::link :href="route('laravel-maker.schema.import-sql')"> {{ __('Import Sql Schema') }} </x-laravel-maker::link>
    </div>
    <div x-on:post-created="alert('New post: ' + $event.detail.title)"></div>
    <x-laravel-maker::card>
        <x-laravel-maker::card-header>
            <h1 class="box-title">Laravel Generator Builder</h1>
        </x-laravel-maker::card-header>
        <x-laravel-maker::card-body>
            <x-laravel-maker::table class="table table-striped table-bordered">
                <x-laravel-maker::thead class="no-border">
                    <x-laravel-maker::tr>
                        <x-laravel-maker::th>Model Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Model</x-laravel-maker::th>
                        <x-laravel-maker::th>Factory</x-laravel-maker::th>
                        <x-laravel-maker::th>Seeder</x-laravel-maker::th>
                        <x-laravel-maker::th>Policy</x-laravel-maker::th>
                        <x-laravel-maker::th>Resource</x-laravel-maker::th>
                        <x-laravel-maker::th>Service</x-laravel-maker::th>
                        <x-laravel-maker::th>All</x-laravel-maker::th>
                        <x-laravel-maker::th>Action</x-laravel-maker::th>
                    </x-laravel-maker::tr>
                </x-laravel-maker::thead>
                <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                    @foreach ($schemas as $schema)
                        <x-laravel-maker::tr>
                            <x-laravel-maker::td>
                                {{ $schema->modelName }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeModel({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeFactory({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeSeeder({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makePolicy({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeResource({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeService({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeAll({{ $schema->id }})" wire:loading.class="animate-spin" />
                            </x-laravel-maker::td>
                            <x-laravel-maker::td class="flex justify-center">
                                <x-laravel-maker::icon-link :href="route('laravel-maker.schema.edit',$schema->id)">
                                    <x-laravel-maker::icons.cog/>
                                </x-laravel-maker::icon-link>

                                <x-laravel-maker::icons.circle class="cursor-pointer text-red-600"
                                                               wire:click="deleteSchema({{ $schema->id }})"/>
                            </x-laravel-maker::td>
                        </x-laravel-maker::tr>
                    @endforeach
                </x-laravel-maker::tbody>
            </x-laravel-maker::table>
        </x-laravel-maker::card-body>
    </x-laravel-maker::card>
</div>
