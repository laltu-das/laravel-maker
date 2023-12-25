<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form id="form">
            <div class="flex">
                @if($mode == 'add')
                    <x-laravel-maker::button type="submit" wire:click="create"> Save</x-laravel-maker::button>
                @else
                    <x-laravel-maker::button type="submit" wire:click="update({{ $schema->id  }})"> Update</x-laravel-maker::button>
                @endif
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFormFieldRow"> Add Field</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFormRelationalFieldRow"> Add RelationShip</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Add Timestamps</x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::input type="text" wire:model="modelName" placeholder="Model Name" required/>

                <x-laravel-maker::table class="table table-striped table-bordered">
                    <x-laravel-maker::thead class="no-border">
                        <x-laravel-maker::tr>
                            <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                            <x-laravel-maker::th>DB Type</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 68px">Primary</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 80px">Is Foreign</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">In Index</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">Nullable</x-laravel-maker::th>
                            <x-laravel-maker::th>Comment</x-laravel-maker::th>
                            <x-laravel-maker::th>Default</x-laravel-maker::th>
                        </x-laravel-maker::tr>
                    </x-laravel-maker::thead>
                    <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                        @foreach ($fields as $index => $row)
                            <x-laravel-maker::tr>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="fields.{{ $index }}.field_name" placeholder="Name" required/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::select wire:model="fields.{{ $index }}.db_type">
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
                                    <x-laravel-maker::checkbox wire:model="fields.{{ $index }}.primary"></x-laravel-maker::checkbox>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="fields.{{ $index }}.isForeign"></x-laravel-maker::checkbox>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="fields.{{ $index }}.inIndex"></x-laravel-maker::checkbox>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="fields.{{ $index }}.nullable"></x-laravel-maker::checkbox>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="fields.{{ $index }}.defaultValue" placeholder="Name" required/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="fields.{{ $index }}.Comment" placeholder="Name" required/>
                                </x-laravel-maker::td>
                                @if($index != 0)
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::icons.circle class="cursor-pointer text-red-600" wire:click="removeFormFieldRow({{ $index }})" />
                                    </x-laravel-maker::td>
                                @endif
                            </x-laravel-maker::tr>
                        @endforeach
                    </x-laravel-maker::tbody>
                </x-laravel-maker::table>
            </div>
            @if(count($relationalFields) > 0)
                <div class="grid gap-4 mb-4 sm:grid-cols-1">
                    <x-laravel-maker::table class="table table-striped table-bordered">
                        <x-laravel-maker::thead class="no-border">
                            <x-laravel-maker::tr>
                                <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                                <x-laravel-maker::th>Relation Type</x-laravel-maker::th>
                                <x-laravel-maker::th>Foreign Model<span class="required">*</span>
                                </x-laravel-maker::th>
                                <x-laravel-maker::th>Foreign Key</x-laravel-maker::th>
                                <x-laravel-maker::th>Local Key</x-laravel-maker::th>
                            </x-laravel-maker::tr>
                        </x-laravel-maker::thead>
                        <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                            @foreach ($relationalFields as $index => $row)
                                <x-laravel-maker::tr>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::input type="text" wire:model="relationalFields.{{ $index }}.fieldName"/>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::select wire:model="relationalFields.{{ $index }}.relationType">
                                            <option value="1t1">One to One</option>
                                            <option value="1tm">One to Many</option>
                                            <option value="mt1">Many to One</option>
                                            <option value="mtm">Many to Many</option>
                                        </x-laravel-maker::select>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::input type="text" required  wire:model="relationalFields.{{ $index }}.foreignModel" />
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::input type="text" wire:model="relationalFields.{{ $index }}.foreignKey"/>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::input type="text" wire:model="relationalFields.{{ $index }}.localKey"/>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::icons.circle class="cursor-pointer text-red-600" wire:click="removeFormRelationalFieldRow({{ $index }})" />
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