<?php


use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Http\Controllers\DashboardController;
use Laltu\LaravelMaker\Http\Controllers\GeneratorController;
use Laltu\LaravelMaker\Http\Controllers\ModuleController;
use Laltu\LaravelMaker\Http\Controllers\SchemaController;
use Laltu\LaravelMaker\Http\Controllers\SettingController;


Route::get('/', [DashboardController::class, 'index'])->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/schema', SchemaController::class);

Route::resource('/module', ModuleController::class);
Route::get('/module/{module}/form', [ModuleController::class, 'index'])->name('module.form');
Route::get('/module/{module}/api', [ModuleController::class, 'index'])->name('module.api');

Route::get('/setting', [SettingController::class, 'index'])->name('setting');
Route::get('/generator', [GeneratorController::class, 'index'])->name('generator');