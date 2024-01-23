<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ScrumController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ToolController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/scrums/getprojectsbyclientid/{client}', [ScrumController::class, 'getProjectsByClientId'])->name('scrums.projects');

Route::get('/tools/csv_to_json', function(){ return view('tools.csv_to_json'); })->name('tools.csv_to_json');
Route::post('/tools/csv_to_json_process', [ToolController::class, 'csvToJson'])->name('tools.csv_to_json_process');

Route::get('/reports/export', function(){ return view('reports.export'); })->name('reports.export');
Route::post('/reports/export', [ReportController::class, 'exportReport'])->name('reports.exportout');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'clients' => ClientController::class,
    'projects' => ProjectController::class,
    'billings' => BillingController::class,
    'scrums' => ScrumController::class,
    'reports' => ReportController::class,
    'tools' => ToolController::class,
]);


