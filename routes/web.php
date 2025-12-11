<?php

use App\Http\Controllers\Api\DocumentationController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DocumentationController::class, 'index'])->name('api.documentation');
