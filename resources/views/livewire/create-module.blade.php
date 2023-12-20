<x-laravel-maker::card class="col-span-8">
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
                <div x-data="{ activeTab: 'tab1' }">
                    <ul class="flex">
                        <li @click="activeTab = 'tab1'" :class="{ 'bg-blue-500': activeTab === 'tab1', 'text-white': activeTab === 'tab1' }" class="cursor-pointer px-4 py-2">Form</li>
                        <li @click="activeTab = 'tab2'" :class="{ 'bg-blue-500': activeTab === 'tab2', 'text-white': activeTab === 'tab2' }" class="cursor-pointer px-4 py-2">Validation</li>
                        <li @click="activeTab = 'tab3'" :class="{ 'bg-blue-500': activeTab === 'tab3', 'text-white': activeTab === 'tab3' }" class="cursor-pointer px-4 py-2">Setting</li>
                        <li @click="activeTab = 'tab3'" :class="{ 'bg-blue-500': activeTab === 'tab3', 'text-white': activeTab === 'tab3' }" class="cursor-pointer px-4 py-2">Api</li>
                    </ul>

                    <div x-show="activeTab === 'tab1'" class="mt-4 p-4 border border-gray-300">
                        {{--                                    <livewire:laravel-maker.form-builder />--}}
                    </div>
                    <div x-show="activeTab === 'tab2'" class="mt-4 p-4 border border-gray-300">
                        {{--                                    <livewire:laravel-maker.create-module />--}}
                    </div>
                    <div x-show="activeTab === 'tab3'" class="mt-4 p-4 border border-gray-300">
                        {{--                                    <livewire:laravel-maker.list-module />--}}
                    </div>
                </div>
            </div>
            {{--                        <div class="flex gap-2">--}}
            {{--                            <x-laravel-maker::button type="button"> Add Primary</x-laravel-maker::button>--}}
            {{--                            <x-laravel-maker::button type="button"> Add Timestamps</x-laravel-maker::button>--}}
            {{--                            <x-laravel-maker::button type="submit" wire:click="submit"> Generate</x-laravel-maker::button>--}}
            {{--                            <x-laravel-maker::button type="button"> Reset</x-laravel-maker::button>--}}
            {{--                        </div>--}}
        </form>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
