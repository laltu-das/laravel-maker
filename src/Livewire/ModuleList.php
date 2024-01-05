<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\Artisan;
use Laltu\LaravelMaker\Models\Module;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleList extends Component
{

    public function generateView(Module $module): void
    {
        Artisan::call('make:inertia-view', ['--resource' => true]);

        $this->js("alert('Update saved!')");
    }

    public function generateController(Module $module): void
    {
        $controllerName = 'YourControllerName';

        // Run the make:controller Artisan command
        Artisan::call('make:controller', ['name' => $controllerName,]);

        // Output the result of the Artisan command
        $output = Artisan::output();

//        $controllerName\n$output

        $this->js("alert('Controller created:')");

    }

    public function generateRequest(Module $module): void
    {

        $this->js("alert('Update saved!')");

    }

    public function generateResponse(Module $module): void
    {

        $this->js("alert('Update saved!')");

    }

    public function generateRoute(Module $module): void
    {

        $this->js("alert('Update saved!')");

    }

    public function render()
    {
        $modules = Module::get();

        return view('laravel-maker::livewire.module-list', compact('modules'))->layout(AppLayout::class);
    }
}