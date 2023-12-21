<div class="mx-auto">
    <div class="flex gap-2">
        <x-laravel-maker::link :href="route('module-create')"> {{ __('Add Module') }} </x-laravel-maker::link>
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
                        <x-laravel-maker::th>Controller Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Model Name</x-laravel-maker::th>
                        <x-laravel-maker::th>Controller Type</x-laravel-maker::th>
                        <x-laravel-maker::th>Controller</x-laravel-maker::th>
                        <x-laravel-maker::th>Request</x-laravel-maker::th>
                        <x-laravel-maker::th>Response</x-laravel-maker::th>
                        <x-laravel-maker::th>View</x-laravel-maker::th>
                        <x-laravel-maker::th>Route</x-laravel-maker::th>
                        <x-laravel-maker::th>Action</x-laravel-maker::th>
                    </x-laravel-maker::tr>
                </x-laravel-maker::thead>
                <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                    @foreach ($modules as $module)
                        <x-laravel-maker::tr>
                            <x-laravel-maker::td>
                                {{ $module->moduleName }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::input type="text" wire:model="module.{{ $module }}.moduleName" placeholder="Name" required="true"/>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::select wire:model="fields.{{ $module }}.modelName">
                                    <option value="increments">Increments</option>
                                    <option value="integer">Integer</option>
                                    <option value="smallInteger">SmallInteger</option>
                                    <option value="longText">LongText</option>
                                    <option value="bigInteger">BigInteger</option>
                                    <option value="double">Double</option>
                                    <option value="float">Float</option>
                                    <option value="decimal">Decimal</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="string">String</option>
                                    <option value="char">Char</option>
                                    <option value="text">Text</option>
                                    <option value="mediumText">MediumText</option>
                                    <option value="longText">LongText</option>
                                    <option value="enum">Enum</option>
                                    <option value="binary">Binary</option>
                                    <option value="dateTime">DateTime</option>
                                    <option value="date">Date</option>
                                    <option value="timestamp">Timestamp</option>
                                </x-laravel-maker::select>
                            </x-laravel-maker::td>
                            <x-laravel-maker::td>
                                <x-laravel-maker::select wire:model="fields.{{ $module }}.controllerType">
                                    <option value="increments">Increments</option>
                                    <option value="integer">Integer</option>
                                    <option value="smallInteger">SmallInteger</option>
                                    <option value="longText">LongText</option>
                                    <option value="bigInteger">BigInteger</option>
                                    <option value="double">Double</option>
                                    <option value="float">Float</option>
                                    <option value="decimal">Decimal</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="string">String</option>
                                    <option value="char">Char</option>
                                    <option value="text">Text</option>
                                    <option value="mediumText">MediumText</option>
                                    <option value="longText">LongText</option>
                                    <option value="enum">Enum</option>
                                    <option value="binary">Binary</option>
                                    <option value="dateTime">DateTime</option>
                                    <option value="date">Date</option>
                                    <option value="timestamp">Timestamp</option>
                                </x-laravel-maker::select>
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
                                <x-laravel-maker::icon-link :href="route('module-view', $module->id)">
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