<?php


use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Livewire\CreateModule;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\ListModule;
use Laltu\LaravelMaker\Livewire\Schema;
use Laltu\LaravelMaker\Livewire\ViewModule;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/schema', Schema::class)->name('schema');
Route::get('/module', ListModule::class)->name('module');
Route::get('/module/create', CreateModule::class)->name('module-create');
Route::get('/module/{module}', ViewModule::class)->name('module-view');