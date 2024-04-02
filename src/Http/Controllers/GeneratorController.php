<?php

namespace Laltu\LaravelMaker\Http\Controllers;

class GeneratorController
{
    public function index()
    {
        return inertia('Generator/GeneratorIndex');
    }
}
