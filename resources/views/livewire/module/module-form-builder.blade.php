<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form id="form">
            <div class="flex">
                <x-laravel-maker::button type="submit" wire:click="update({{ $module->id  }})"> Update</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFormFieldRow"> Add Field</x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::table class="table table-striped table-bordered">
                    <x-laravel-maker::thead class="no-border">
                        <x-laravel-maker::tr>
                            <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                            <x-laravel-maker::th>Field Type</x-laravel-maker::th>
                            <x-laravel-maker::th>Field Label</x-laravel-maker::th>
                            <x-laravel-maker::th>Field Placeholder</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 68px">Row</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 80px">Column</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">Action</x-laravel-maker::th>
                        </x-laravel-maker::tr>
                    </x-laravel-maker::thead>
                    <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                        @foreach ($formFields as $index => $row)
                            <x-laravel-maker::tr>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.fieldName" placeholder="Field Name"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::select wire:model="formFields.{{ $index }}.fieldType">
                                        <option value="button">Button</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="color">Color</option>
                                        <option value="date">Date</option>
                                        <option value="datetime-local">Datetime Local</option>
                                        <option value="email">Email</option>
                                        <option value="file">File</option>
                                        <option value="hidden">Hidden</option>
                                        <option value="image">Image</option>
                                        <option value="month">Month</option>
                                        <option value="number">Number</option>
                                        <option value="password">Password</option>
                                        <option value="radio">Radio</option>
                                        <option value="range">Range</option>
                                        <option value="reset">Reset</option>
                                        <option value="search">Search</option>
                                        <option value="submit">Submit</option>
                                        <option value="tel">Tel</option>
                                        <option value="text">Text</option>
                                        <option value="time">Time</option>
                                        <option value="url">URL</option>
                                        <option value="week">Week</option>
                                    </x-laravel-maker::select>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.fieldLabel" placeholder="Label"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="formFields.{{ $index }}.fieldPlaceholder" placeholder="Placeholder"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox checked="true" name="" wire:model="formFields.{{ $index }}.fieldRow"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="number" min="1" max="12" wire:model="formFields.{{ $index }}.fieldCol" placeholder="Col"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::icons.cog class="cursor-pointer text-blue-600 m-auto text-center" wire:click="show({{ $index }})" />
                                </x-laravel-maker::td>
                                @if($index != 0)
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::icons.circle class="cursor-pointer text-red-600 m-auto text-center" wire:click="removeFormFieldRow({{ $index }})" />
                                    </x-laravel-maker::td>
                                @endif
                            </x-laravel-maker::tr>
                        @endforeach
                    </x-laravel-maker::tbody>
                </x-laravel-maker::table>
            </div>
        </form>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
