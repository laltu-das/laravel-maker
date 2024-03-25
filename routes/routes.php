<?php


use Illuminate\Support\Facades\Route;

use Laltu\LaravelMaker\Livewire\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\ModuleCreate;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\ModuleValidation;
use Laltu\LaravelMaker\Livewire\SchemaCreate;
use Laltu\LaravelMaker\Livewire\SchemaImportFromSql;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\SchemaUpdate;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\Generator;

Route::name('laravel-maker.')->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/schema', SchemaList::class)->name('schema');
    Route::get('/schema/import-sql', SchemaImportFromSql::class)->name('schema.import-sql');
    Route::get('/schema/create', SchemaCreate::class)->name('schema.create');
    Route::get('/schema/{schemaId}', SchemaUpdate::class)->name('schema.edit');

    Route::get('/setting', Setting::class)->name('setting');
    Route::get('/generator', Generator::class)->name('generator');

    Route::get('/module', ModuleList::class)->name('module');
    Route::get('/module/create', ModuleCreate::class)->name('module.create');
    Route::get('/module/{moduleId}', ModuleCreate::class)->name('module.edit');
    Route::get('/module/{moduleId}/validation', ModuleValidation::class)->name('module-validation');
    Route::get('/module/{moduleId}/form', ModuleFormBuilder::class)->name('module-form');
    Route::get('/module/{moduleId}/api', ModuleApiBuilder::class)->name('module-api');
});