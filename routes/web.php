<?php


use Illuminate\Support\Facades\Route;

use Laltu\LaravelMaker\Livewire\CreateModule;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\Generator;
use Laltu\LaravelMaker\Livewire\ViewModule;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/schema', SchemaList::class)->name('schema');
Route::get('/setting', Setting::class)->name('setting');
Route::get('/generator', Generator::class)->name('generator');
Route::get('/module', ModuleList::class)->name('module');
Route::get('/module/create', CreateModule::class)->name('module-create');
Route::get('/module/{module}', ViewModule::class)->name('module-view');