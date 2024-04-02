<?php

namespace Laltu\LaravelMaker\Http\Controllers;

class DashboardController
{
    public function index()
    {
        return inertia('Dashboard');
    }
}
