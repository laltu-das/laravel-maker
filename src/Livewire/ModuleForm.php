<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Models\Module;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleForm extends Component
{
    public Module $module;
    public string $moduleName;
    public string $controllerType;
    public string $controllerName;
    
    public string $mode;

    protected $rules = [
        'moduleName' => 'required|string|max:255',
        // Add other field validations as necessary
    ];

    public function mount(?int $moduleId = null): void
    {
        $this->module = Module::findOrNew($moduleId);
        $this->mode = $moduleId ? 'edit' : 'add';
        if ($this->mode === 'edit') {
            $this->fill($this->module->toArray());
        }
    }

    public function save()
    {
        $this->validate();

        $this->module->fill([
            'moduleName' => $this->moduleName,
            'controllerType' => $this->controllerType,
            'controllerName' => $this->controllerName,
        ])->save();

        session()->flash('message', 'Module ' . $this->mode . 'ed successfully.');

        return redirect()->route('module');
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-form')->layout(AppLayout::class);
    }
}
