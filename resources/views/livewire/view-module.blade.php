<!-- resources/views/livewire/tabs-component.blade.php -->

<div x-data="{ activeTab: 'tab1' }">
    <ul class="flex">
        <li @click="activeTab = 'tab1'" :class="{ 'bg-blue-500': activeTab === 'tab1', 'text-white': activeTab === 'tab1' }" class="cursor-pointer px-4 py-2">Tab 1</li>
        <li @click="activeTab = 'tab2'" :class="{ 'bg-blue-500': activeTab === 'tab2', 'text-white': activeTab === 'tab2' }" class="cursor-pointer px-4 py-2">Tab 2</li>
        <li @click="activeTab = 'tab3'" :class="{ 'bg-blue-500': activeTab === 'tab3', 'text-white': activeTab === 'tab3' }" class="cursor-pointer px-4 py-2">Tab 3</li>
    </ul>

    <div x-show="activeTab === 'tab1'" class="mt-4 p-4 border border-gray-300">
        <livewire:laravel-maker.form-builder />
    </div>
    <div x-show="activeTab === 'tab2'" class="mt-4 p-4 border border-gray-300">
        <livewire:laravel-maker.create-module />
    </div>
    <div x-show="activeTab === 'tab3'" class="mt-4 p-4 border border-gray-300">
        <livewire:laravel-maker.list-module />
    </div>
</div>
