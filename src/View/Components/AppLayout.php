<?php

namespace Laltu\LaravelMaker\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Renders the view for the app layout component.
     *
     * @return View The rendered view for the app layout component.
     */
    public function render(): View
    {
        return view('laravel-maker::components.app-layout');
    }
}
