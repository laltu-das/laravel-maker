<?php


use Illuminate\Support\Facades\Route;

use Laltu\LaravelMaker\Livewire\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\ModuleForm;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\ModuleValidation;
use Laltu\LaravelMaker\Livewire\SchemaForm;
use Laltu\LaravelMaker\Livewire\SchemaImportFromSql;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\Generator;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/schema', SchemaList::class)->name('schema');
Route::get('/schema/import-sql', SchemaImportFromSql::class)->name('schema.import-sql');
Route::get('/schema/create', SchemaForm::class)->name('schema.create');
Route::get('/schema/{schemaId}', SchemaForm::class)->name('schema.view');

Route::get('/setting', Setting::class)->name('setting');
Route::get('/generator', Generator::class)->name('generator');

Route::get('/module', ModuleList::class)->name('module');
Route::get('/module/create', ModuleForm::class)->name('module-create');
Route::get('/module/{moduleId}', ModuleForm::class)->name('module-view');
Route::get('/module/{moduleId}/validation', ModuleValidation::class)->name('module-validation');
Route::get('/module/{moduleId}/form', ModuleFormBuilder::class)->name('module-form');
Route::get('/module/{moduleId}/api', ModuleApiBuilder::class)->name('module-api');