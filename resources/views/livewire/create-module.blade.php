<div>
    <div class="col-md-10 col-md-offset-1">
        <section class="content">
            <x-laravel-maker::card>
                <x-laravel-maker::card-header>
                    <h1 class="box-title">InfyOm Laravel Generator Builder</h1>
                </x-laravel-maker::card-header>
                <x-laravel-maker::card-body>
                    <form id="form">
                        <div class="">
                            <div class="grid gap-4 mb-4 sm:grid-cols-5">
                                <div class="form-group col-md-4">
                                    <x-laravel-maker::label for="txtModelName">Model Name<span class="required">*</span>
                                    </x-laravel-maker::label>
                                    <x-laravel-maker::input type="text" required placeholder="Enter name"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-laravel-maker::label for="drdCommandType">Command Type</x-laravel-maker::label>
                                    <x-laravel-maker::select>
                                        <option value="infyom:api_scaffold">API Scaffold Generator</option>
                                        <option value="infyom:api">API Generator</option>
                                        <option value="infyom:scaffold">Scaffold Generator</option>
                                    </x-laravel-maker::select>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-laravel-maker::label for="txtCustomTblName">Custom Table Name
                                    </x-laravel-maker::label>
                                    <x-laravel-maker::input type="text" placeholder="Enter table name"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <x-laravel-maker::label for="txtPrefix">Prefix</x-laravel-maker::label>
                                    <x-laravel-maker::input type="text" placeholder="Enter prefix"/>
                                </div>

                                <div class="form-group col-md-1">
                                    <x-laravel-maker::label for="txtPaginate">Paginate</x-laravel-maker::label>
                                    <x-laravel-maker::input type="number" value="10" placeholder=""/>
                                </div>
                            </div>
                            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                                <x-laravel-maker::label for="txtModelName">Options</x-laravel-maker::label>
                                <div class="grid gap-4 mb-4 sm:grid-cols-10">
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Soft Delete</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Save Schema</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Swagger</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Test Cases</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Datatables</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Migration</x-laravel-maker::checkbox>
                                    </div>
                                    <div class="checkbox chk-align">
                                        <x-laravel-maker::checkbox>Force Migrate</x-laravel-maker::checkbox>
                                    </div>
                                </div>
                            </div>
                            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                                <div class="form-group col-md-12">
                                    <div class="form-control">
                                        <x-laravel-maker::label style="font-size: 18px">Fields</x-laravel-maker::label>
                                    </div>
                                </div>
                                <x-laravel-maker::table class="table table-striped table-bordered">
                                    <x-laravel-maker::thead class="no-border">
                                        <x-laravel-maker::tr>
                                            <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                                            <x-laravel-maker::th>DB Type</x-laravel-maker::th>
                                            <x-laravel-maker::th>Validations</x-laravel-maker::th>
                                            <x-laravel-maker::th>Html Type</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 68px">Primary</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 80px">Is Foreign</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 87px">Searchable</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 63px">Fillable</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 65px">In Form</x-laravel-maker::th>
                                            <x-laravel-maker::th style="width: 67px">In Index</x-laravel-maker::th>
                                            <x-laravel-maker::th>
                                                <x-laravel-maker::button type="button" wire:click="addFormFieldRow"> Add Field</x-laravel-maker::button>
                                            </x-laravel-maker::th>
                                        </x-laravel-maker::tr>
                                    </x-laravel-maker::thead>
                                    <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                                        @foreach ($formFields as $index => $row)
                                            <x-laravel-maker::tr>
                                                <x-laravel-maker::td>
                                                    <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.field_name" placeholder="Name" required/>
{{--                                                    <x-laravel-maker::input type="text" wire:model="rows.{{ $index }}.email" placeholder="Foreign table,Primary key"/>--}}
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
                                                    <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.validation" placeholder="Validation"/>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                    <x-laravel-maker::select wire:model="formFields.{{ $index }}.html_type" placeholder="Html type">
                                                        <option value="text">Text</option>
                                                        <option value="email">Email</option>
                                                        <option value="number">Number</option>
                                                        <option value="date">Date</option>
                                                        <option value="file">File</option>
                                                        <option value="password">Password</option>
                                                        <option value="select">Select</option>
                                                        <option value="radio">Radio</option>
                                                        <option value="checkbox">Checkbox</option>
                                                        <option value="textarea">TextArea</option>
                                                        <option value="toggle-switch">Toggle</option>
                                                    </x-laravel-maker::select>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                    <x-laravel-maker::checkbox wire:model="formFields.{{ $index }}.primary"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                        <x-laravel-maker::checkbox wire:model="formFields.{{ $index }}.is_foreign"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                        <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.searchable"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                        <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.fillable"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                        <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.in_form"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                    <x-laravel-maker::checkbox checked wire:model="formFields.{{ $index }}.in_index"></x-laravel-maker::checkbox>
                                                </x-laravel-maker::td>
                                                <x-laravel-maker::td>
                                                    <button type="button" wire:click="removeFormFieldRow({{ $index }})">Remove</button>
                                                </x-laravel-maker::td>
                                            </x-laravel-maker::tr>
                                        @endforeach
                                    </x-laravel-maker::tbody>
                                </x-laravel-maker::table>
                            </div>
                            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                                <x-laravel-maker::table class="table table-striped table-bordered">
                                    <x-laravel-maker::thead class="no-border">
                                        <x-laravel-maker::tr>
                                            <x-laravel-maker::th>Relation Type</x-laravel-maker::th>
                                            <x-laravel-maker::th>Foreign Model<span class="required">*</span>
                                            </x-laravel-maker::th>
                                            <x-laravel-maker::th>Foreign Key</x-laravel-maker::th>
                                            <x-laravel-maker::th>Local Key</x-laravel-maker::th>
                                            <x-laravel-maker::th>
                                                <x-laravel-maker::button type="button" wire:click="addFormRelationalFieldRow"> Add RelationShip</x-laravel-maker::button>
                                            </x-laravel-maker::th>
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
                                                    <button type="button" wire:click="removeFormRelationalFieldRow({{ $index }})">Remove</button>
                                                </x-laravel-maker::td>
                                            </x-laravel-maker::tr>
                                        @endforeach
                                    </x-laravel-maker::tbody>
                                </x-laravel-maker::table>
                            </div>
                        </div>
                        <div class="flex gap-2">
{{--                            <x-laravel-maker::button type="button"> Add Primary</x-laravel-maker::button>--}}
{{--                            <x-laravel-maker::button type="button"> Add Timestamps</x-laravel-maker::button>--}}
                            <x-laravel-maker::button type="submit" wire:click="submit"> Generate</x-laravel-maker::button>
{{--                            <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>--}}
                        </div>
                    </form>
                </x-laravel-maker::card-body>
            </x-laravel-maker::card>
        </section>
    </div>
</div>