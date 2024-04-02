<?php

namespace Laltu\LaravelMaker\Http\Controllers;

class ModuleController
{
    public function index()
    {
        return inertia('Module/ModuleIndex');
    }
}
