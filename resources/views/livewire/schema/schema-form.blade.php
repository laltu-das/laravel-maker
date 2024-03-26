<x-laravel-maker::card>
    <x-laravel-maker::card-body>
        <form wire:submit="save">
            <div class="flex">
                <x-laravel-maker::button type="submit" wire:click="save"> Save</x-laravel-maker::button>
                <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addFieldRow"> Add Field</x-laravel-maker::button>
                <x-laravel-maker::button type="button" wire:click="addRelationshipFieldRow"> Add Relationship
                </x-laravel-maker::button>
            </div>
            <div class="grid gap-4 mb-4 sm:grid-cols-1">
                <x-laravel-maker::input type="text" wire:model="form.model_name" placeholder="Model Name" required/>
                <div>
                    @error('form.modelName') <span class="error">{{ $message }}</span> @enderror
                </div>
                <x-laravel-maker::table class="table table-striped table-bordered">
                    <x-laravel-maker::thead class="no-border">
                        <x-laravel-maker::tr>
                            <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                            <x-laravel-maker::th>DB Type</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">Primary</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">Unique</x-laravel-maker::th>
                            <x-laravel-maker::th style="width: 67px">Nullable</x-laravel-maker::th>
                            <x-laravel-maker::th>Field Type</x-laravel-maker::th>
                            <x-laravel-maker::th>Relation Type</x-laravel-maker::th>
                            <x-laravel-maker::th>Relation Model</x-laravel-maker::th>
                            <x-laravel-maker::th>Foreign Key</x-laravel-maker::th>
                            <x-laravel-maker::th>Comment</x-laravel-maker::th>
                            <x-laravel-maker::th>Default</x-laravel-maker::th>
                        </x-laravel-maker::tr>
                    </x-laravel-maker::thead>
                    <x-laravel-maker::tbody class="no-border-x no-border-y">
                        @foreach ($form->fields as $index => $field)
                            <x-laravel-maker::tr>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.fields.{{ $index }}.fieldName" placeholder="Name" required/>
                                    @error("form.fields.{$index}.fieldName") <span>{{ $message }}</span> @enderror
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::select wire:model="form.fields.{{ $index }}.dataType" :options="$form->dataTypeOptions"/>
                                    @error("form.fields.{$index}.dataType") <span>{{ $message }}</span> @enderror
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="form.fields.{{ $index }}.primary"></x-laravel-maker::checkbox>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="form.fields.{{ $index }}.unique"></x-laravel-maker::checkbox>
                                    <p>@error("form.fields.{$index}.unique") <span class="error">{{ $message }}</span> @enderror</p>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::checkbox wire:model="form.fields.{{ $index }}.nullable"></x-laravel-maker::checkbox>
                                    <p>@error("form.fields.{$index}.nullable") <span class="error">{{ $message }}</span> @enderror</p>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td> {{ $field['fieldType']  }}                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    @if($field['fieldType'] == 'relationship')
                                        <x-laravel-maker::select wire:model="form.fields.{{ $index }}.relationType"
                                                                 :options="$form->relationshipOptions"/>
                                    @endif
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    @if($field['fieldType'] == 'relationship')
                                        <x-laravel-maker::select type="text" required
                                                                 wire:model="form.fields.{{ $index }}.foreignModel"
                                                                 :options="$field['foreignModelOptions']"/>
                                    @endif
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    @if($field['fieldType'] == 'relationship')
                                        <x-laravel-maker::input type="text"
                                                                wire:model="form.fields.{{ $index }}.foreignKey"/>
                                    @endif
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.fields.{{ $index }}.defaultValue"
                                                            placeholder="Default Value" required/>
                                </x-laravel-maker::td>
                                <x-laravel-maker::td>
                                    <x-laravel-maker::input type="text" wire:model="form.fields.{{ $index }}.comment"
                                                            placeholder="Comment" required/>
                                </x-laravel-maker::td>
                                @if($index != 0)
                                    <x-laravel-maker::td>
                                        <x-laravel-maker::icons.circle class="cursor-pointer text-red-600"
                                                                       wire:click="removeFormFieldRow({{ $index }})"/>
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