<?php

namespace Laltu\LaravelMaker\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Laltu\LaravelMaker\LaravelMaker;

class HomeController extends Controller
{
    /**
     * Display the Telescope view.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('laravel-maker::layout', [
            'laravelMakerScriptVariables' => LaravelMaker::scriptVariables(),
        ]);
    }
}
