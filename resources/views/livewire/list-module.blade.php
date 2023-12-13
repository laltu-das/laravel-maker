<div>
    <div class="col-md-10 col-md-offset-1">
        <section class="content">
            <div class="box box-primary col-lg-12">
                <div class="box-header">
                    <h1 class="box-title">InfyOm Laravel Generator Builder</h1>
                </div>
                <div class="box-body">
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
                                <x-laravel-maker::th></x-laravel-maker::th>
                            </x-laravel-maker::tr>
                        </x-laravel-maker::thead>
                        <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                            @foreach ($formFields as $index => $row)
                                <x-laravel-maker::tr>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::input type="text" wire:model="rows.{{ $index }}.name" placeholder="Name" required/>
                                        {{--                                                    <x-laravel-maker::input type="text" wire:model="rows.{{ $index }}.email" placeholder="Foreign table,Primary key"/>--}}
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::select>
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
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::input type="text"/>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::select >
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
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox checked></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox checked></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox checked></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="vertical-align: middle">
                                        <x-laravel-maker::checkbox checked></x-laravel-maker::checkbox>
                                    </x-laravel-maker::td>
                                    <x-laravel-maker::td style="text-align: center;vertical-align: middle">
                                        <button type="button" wire:click="removeFormFieldRow({{ $index }})">Remove</button>
                                    </x-laravel-maker::td>
                                </x-laravel-maker::tr>
                            @endforeach
                        </x-laravel-maker::tbody>
                    </x-laravel-maker::table>
                </div>
            </div>
        </section>
    </div>
</div>