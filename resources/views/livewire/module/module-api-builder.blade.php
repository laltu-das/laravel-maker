<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form id="form">
            <div class="flex">
                <x-laravel-maker::button type="submit" wire:click="update"> Update</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addApiFieldRow"> Add Field</x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::table class="table table-striped table-bordered">
                    <x-laravel-maker::thead class="no-border">
                        <x-laravel-maker::tr>
                            <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                            <x-laravel-maker::th>Field Type</x-laravel-maker::th>
                            <x-laravel-maker::th>POST Request Validation</x-laravel-maker::th>
                            <x-laravel-maker::th>PUT Request Validation</x-laravel-maker::th>
                            <x-laravel-maker::th>Action</x-laravel-maker::th>
                        </x-laravel-maker::tr>
                    </x-laravel-maker::thead>
                    <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                        @foreach ($form->api_fields as $index => $field)
                            <x-laravel-maker::tr>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.api_fields.{{ $index }}.fieldName" placeholder="Field Name"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::select wire:model="form.api_fields.{{ $index }}.fieldType" :options="$form->apiFieldsFieldTypeOptions"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.api_fields.{{ $index }}.fieldName" placeholder="Field Name"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.api_fields.{{ $index }}.fieldName" placeholder="Field Name"/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::icons.cog class="cursor-pointer text-blue-600 m-auto text-center" wire:click="show({{ $index }})" />
                                </x-laravel-maker::td>
                                @if($index != 0)
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::icons.circle class="cursor-pointer text-red-600 m-auto text-center" wire:click="removeApiFieldRow({{ $index }})" />
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
