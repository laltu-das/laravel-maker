<div class="mx-auto grid gap-4 mb-4 grid-cols-12 justify-between">
    <x-laravel-maker::card class="col-span-4">
        <x-laravel-maker::card-header>
            <h1 class="box-title">InfyOm Laravel Generator Builder</h1>
        </x-laravel-maker::card-header>
        <x-laravel-maker::card-body>
            <x-laravel-maker::table class="table table-striped table-bordered">
                <x-laravel-maker::thead class="no-border">
                    <x-laravel-maker::tr>
                        <x-laravel-maker::th>Model Nme</x-laravel-maker::th>
                        <x-laravel-maker::th>Action</x-laravel-maker::th>
                    </x-laravel-maker::tr>
                </x-laravel-maker::thead>
                <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                    @foreach ($schemas as $index => $row)
                        <x-laravel-maker::tr>
                            <x-laravel-maker::td>
                                {{ $row->name }}
                            </x-laravel-maker::td>
                            <x-laravel-maker::td class="flex justify-center">
                                <x-laravel-maker::icons.x-cog class="cursor-pointer" wire:click="show('{{ $row->name }}')"/>
                            </x-laravel-maker::td>
                        </x-laravel-maker::tr>
                    @endforeach
                </x-laravel-maker::tbody>
            </x-laravel-maker::table>
        </x-laravel-maker::card-body>
    </x-laravel-maker::card>
    <x-laravel-maker::card class="col-span-8">
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
                    <x-laravel-maker::input type="text" wire:model="table_name" placeholder="Model Name" required/>

                    <x-laravel-maker::table class="table table-striped table-bordered">
                        <x-laravel-maker::thead class="no-border">
                            <x-laravel-maker::tr>
                                <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                                <x-laravel-maker::th>DB Type</x-laravel-maker::th>
                                <x-laravel-maker::th style="width: 68px">Primary</x-laravel-maker::th>
                                <x-laravel-maker::th style="width: 80px">Is Foreign</x-laravel-maker::th>
                                <x-laravel-maker::th style="width: 67px">In Index</x-laravel-maker::th>
                                <x-laravel-maker::th style="width: 67px">Nullable</x-laravel-maker::th>
                            </x-laravel-maker::tr>
                        </x-laravel-maker::thead>
                        <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                            @foreach ($formFields as $index => $row)
                                <x-laravel-maker::tr>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.field_name" placeholder="Name" required/>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::select wire:model="formFields.{{ $index }}.db_type">
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
                                        <x-laravel-maker::checkbox wire:model="formFields.{{ $index }}.primary"></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::checkbox wire:model="formFields.{{ $index }}.is_foreign"></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.in_index"></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.nullable"></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    @if($index != 0)
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::icons.x-circle class="cursor-pointer" wire:click="removeFormFieldRow({{ $index }})" />
                                        </x-laravel-maker::td>
                                    @endif
                                </x-laravel-maker::tr>
                            @endforeach
                        </x-laravel-maker::tbody>
                    </x-laravel-maker::table>
                </div>
                @if(count($formRelationalFields) > 0)
                    <div class="grid gap-4 mb-4 sm:grid-cols-1">
                        <x-laravel-maker::table class="table table-striped table-bordered">
                            <x-laravel-maker::thead class="no-border">
                                <x-laravel-maker::tr>
                                    <x-laravel-maker::th>Relation Type</x-laravel-maker::th>
                                    <x-laravel-maker::th>Foreign Model<span class="required">*</span>
                                    </x-laravel-maker::th>
                                    <x-laravel-maker::th>Foreign Key</x-laravel-maker::th>
                                    <x-laravel-maker::th>Local Key</x-laravel-maker::th>
                                </x-laravel-maker::tr>
                            </x-laravel-maker::thead>
                            <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                                @foreach ($formRelationalFields as $index => $row)
                                    <x-laravel-maker::tr>
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::select  wire:model="formRelationalFields.{{ $index }}.relation_type">
                                                <option value="1t1">One to One</option>
                                                <option value="1tm">One to Many</option>
                                                <option value="mt1">Many to One</option>
                                                <option value="mtm">Many to Many</option>
                                            </x-laravel-maker::select>
                                        </x-laravel-maker::td>
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::input type="text" required  wire:model="formRelationalFields.{{ $index }}.foreign_model" />
                                        </x-laravel-maker::td>
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::input type="text" wire:model="formRelationalFields.{{ $index }}.foreign_key"/>
                                        </x-laravel-maker::td>
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::input type="text" wire:model="formRelationalFields.{{ $index }}.local_key"/>
                                        </x-laravel-maker::td>
                                        <x-laravel-maker::td>
                                            <x-laravel-maker::icons.x-circle class="cursor-pointer" wire:click="removeFormRelationalFieldRow({{ $index }})" />
                                        </x-laravel-maker::td>
                                    </x-laravel-maker::tr>
                                @endforeach
                            </x-laravel-maker::tbody>
                        </x-laravel-maker::table>
                    </div>
                @endif
            </form>
        </x-laravel-maker::card-body>
    </x-laravel-maker::card>
</div>
