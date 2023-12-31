<div class="mx-auto">
    <div class="flex gap-2">
        <x-laravel-maker::link :href="route('schema-create')"> {{ __('Add Schema') }} </x-laravel-maker::link>
    </div>
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
                        <x-laravel-maker::th>Migration</x-laravel-maker::th>
                        <x-laravel-maker::th>Factory</x-laravel-maker::th>
                        <x-laravel-maker::th>Seeder</x-laravel-maker::th>
                        <x-laravel-maker::th>Policy</x-laravel-maker::th>
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
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer" wire:click="makeModel({{ $schema->id }})"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::icons.reload class="m-auto cursor-pointer"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td class="flex justify-center">
                                <x-laravel-maker::icon-link :href="route('schema-view',$schema->id)">
                                    <x-laravel-maker::icons.cog/>
                                </x-laravel-maker::icon-link>
                            </x-laravel-maker::td>
                        </x-laravel-maker::tr>
                    @endforeach
                </x-laravel-maker::tbody>
            </x-laravel-maker::table>
        </x-laravel-maker::card-body>
    </x-laravel-maker::card>
</div>
