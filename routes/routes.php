<?php


use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\Generator;
use Laltu\LaravelMaker\Livewire\Module\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\Module\ModuleCreate;
use Laltu\LaravelMaker\Livewire\Module\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\Module\ModuleList;
use Laltu\LaravelMaker\Livewire\Module\ModuleUpdate;
use Laltu\LaravelMaker\Livewire\Module\ModuleValidation;
use Laltu\LaravelMaker\Livewire\Schema\SchemaCreate;
use Laltu\LaravelMaker\Livewire\Schema\SchemaImportFromSql;
use Laltu\LaravelMaker\Livewire\Schema\SchemaList;
use Laltu\LaravelMaker\Livewire\Schema\SchemaUpdate;
use Laltu\LaravelMaker\Livewire\Setting;

Route::name('laravel-maker.')->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/schema', SchemaList::class)->name('schema');
    Route::get('/schema/import-sql', SchemaImportFromSql::class)->name('schema.import-sql');
    Route::get('/schema/create', SchemaCreate::class)->name('schema.create');
    Route::get('/schema/{schema}', SchemaUpdate::class)->name('schema.edit');

    Route::get('/setting', Setting::class)->name('setting');
    Route::get('/generator', Generator::class)->name('generator');

    Route::get('/module', ModuleList::class)->name('module');
    Route::get('/module/create', ModuleCreate::class)->name('module.create');
    Route::get('/module/{module}', ModuleUpdate::class)->name('module.edit');
    Route::get('/module/{module}/validation', ModuleValidation::class)->name('module.validation');
    Route::get('/module/{module}/form', ModuleFormBuilder::class)->name('module.form');
    Route::get('/module/{module}/api', ModuleApiBuilder::class)->name('module.api');
});