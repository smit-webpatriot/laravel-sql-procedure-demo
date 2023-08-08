<?php

use App\Http\Controllers\ProjectController;
use App\Models\Keyword;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/import', [ProjectController::class, 'import'])->name('projects.import');
