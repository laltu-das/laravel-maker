<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Models\Module;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleCreate extends Component
{
    public Module $module;

    public string $mode = 'add';
    public string $moduleName;
    public string $controllerType;
    public string $controllerName;

    public function mount(): void
    {
        $module = Route::current()->parameter('module');

        if ($module) {
            $this->mode = 'edit';
            $this->fill($this->module);
        }
    }

    public function create(): void
    {
        Module::create([
            'moduleName' => $this->moduleName,
            'controllerType' => $this->controllerType,
            'controllerName' => $this->controllerName,
        ]);

        $this->redirect(route('module'),true);
    }

    public function update(Module $module): void
    {
        $module->update([
            'moduleName' => $this->moduleName,
            'controllerType' => $this->controllerType,
            'controllerName' => $this->controllerName,
        ]);

        $this->redirect(route('module'),true);
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-create')->layout(AppLayout::class);
    }
}