<x-laravel-maker::card>
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
                <x-laravel-maker::input type="text" wire:model="table_name" placeholder="Model Name" required="true"/>
            </div>
        </form>
        <div class="flex h-screen bg-gray-900">
            <!-- Sidebar -->
            <div class="w-64 sidebar bg-black">
                <!-- Sidebar content here -->
            </div>

            <!-- Main content -->
            <div class="flex-1 main-content">
                <!-- Header -->
                <div class="bg-teal-500 p-4 text-white text-lg font-bold">
                    Generate Code
                </div>

                <!-- Generation Options -->
                <div class="p-4">
                    <div class="text-white mb-2">Generation Options</div>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                        <span class="ml-2 text-gray-400">Delete files from removed modules</span>
                    </div>
                    <!-- ... other options ... -->
                </div>

                <!-- Project Base -->
                <div class="p-4">
                    <div class="text-white mb-2">Project Base</div>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- ... Project Base options ... -->
                    </div>
                </div>

                <!-- License Expired Notification -->
                <div class="bg-red-600 text-center p-2 text-white">
                    License Expired - Please acquire a license here to generate the code
                </div>

                <!-- Footer -->
                <div class="p-4 text-right">
                    <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                        Run
                    </button>
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-900 flex">
            <!-- Sidebar -->
            <div class="w-64 bg-black p-6">
                <!-- Sidebar content goes here -->
            </div>

            <!-- Main content area -->
            <div class="flex-1">
                <!-- Header -->
                <div class="bg-green-500 text-white p-4">
                    <h1 class="text-xl font-bold">Slots for Court Alpha</h1>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Action buttons -->
                    <div class="mb-4">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Select All Applications
                        </button>
                        <button class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded ml-2">
                            Unselect All Applications
                        </button>
                    </div>

                    <!-- Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Repeat this for each card -->
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="mb-2 font-bold text-white">Auth Scaffold with Breeze</div>
                            <div class="text-gray-400">Permissions - API Endpoints</div>
                            <!-- ... other card content ... -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-laravel-maker::card-body>
</x-laravel-maker::card>
