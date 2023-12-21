<?php


use Illuminate\Support\Facades\Route;

use Laltu\LaravelMaker\Livewire\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\ModuleCreate;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\SchemaCreate;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\Generator;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/schema', SchemaList::class)->name('schema');
Route::get('/schema/create', SchemaCreate::class)->name('schema-create');
Route::get('/schema/{schema}', SchemaCreate::class)->name('schema-view');

Route::get('/setting', Setting::class)->name('setting');
Route::get('/generator', Generator::class)->name('generator');

Route::get('/module', ModuleList::class)->name('module');
Route::get('/module/create', ModuleCreate::class)->name('module-create');
Route::get('/module/{module}', ModuleCreate::class)->name('module-view');
Route::get('/module/{module}/form', ModuleFormBuilder::class)->name('module-form');
Route::get('/module/{module}/api', ModuleApiBuilder::class)->name('module-api');