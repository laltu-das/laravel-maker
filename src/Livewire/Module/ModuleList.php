<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Illuminate\Support\Facades\Artisan;
use Laltu\LaravelMaker\Models\Module;
use Livewire\Component;

class ModuleList extends Component
{
    public function generateTable($moduleName): void
    {
        // Assuming you have a way to specify the view path or options
        Artisan::call('make:inertia-table', ['name' => $moduleName, '--resource' => true]);

        session()->flash('message', 'View for ' . $moduleName . ' generated successfully!');
    }

    public function generateView($moduleName): void
    {
        // Assuming you have a way to specify the view path or options
        Artisan::call('make:inertia-view', ['name' => $moduleName, '--resource' => true]);

        session()->flash('message', 'View for ' . $moduleName . ' generated successfully!');
    }

    public function generateForm(Module $module): void
    {
        // Assuming you have a way to specify the view path or options
        Artisan::call('make:inertia-form', [
            'name' => $module->module_name,
            '--stack' => 'vue',
            '--model' => $module->model_name,
            '--route' => $module->route_name,
            '--fields' => $module->fields,
            '--force' => true,
        ]);

        session()->flash('message', 'View for ' . $module->module_name . ' generated successfully!');
    }

    public function generateController($moduleName): void
    {
        $controllerName = $moduleName . 'Controller';

        Artisan::call('make:controller', ['name' => $controllerName]);

        session()->flash('message', 'Controller ' . $controllerName . ' created successfully!');
    }

    public function generateRequest($moduleName): void
    {
        // Example for generating a request
        $requestName = $moduleName . 'Request';

        Artisan::call('make:request', ['name' => $requestName]);

        session()->flash('message', 'Request ' . $requestName . ' created successfully!');
    }

    public function deleteModule(Module $module): void
    {
        $module->delete();
    }

    public function moduleCreate(): void
    {
//        dd('');
        $this->dispatch('open-modal')->to(ModuleCreate::class);
    }

    public function moduleEdit(): void
    {
        $this->dispatch('post-created')->to(ModuleCreate::class);
    }

    public function render()
    {

        $modules = Module::latest()->get();

        return view('laravel-maker::livewire.module.module-list', compact('modules'))->layout('laravel-maker::components.layouts.app');
    }
}
