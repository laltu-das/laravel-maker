<?php

namespace Laltu\LaravelMaker\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public function render(): View
    {
        return view('laravel-maker::components.app-layout');
    }
}
