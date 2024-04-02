<?php

namespace Laltu\LaravelMaker\Http\Controllers;

class SettingController
{
    public function index()
    {
        return inertia('Setting/SettingIndex');
    }
}
