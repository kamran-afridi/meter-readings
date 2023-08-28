<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ReadingActionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Login Routes
 */
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
/**
 * Register Routes
 */
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/* only Auth user will access these routes */

// Route::group(['middleware' => ['auth']], function () {
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::resource('meters', ActionController::class);
Route::POST('meter', [ActionController::class, 'create'])->name('create.estimated.reading');
Route::Get('view-est-reading/{id}', [ActionController::class, 'view_est_reading'])->name('meters.view_est_reading');
Route::resource('meter-reading', ReadingActionController::class);
// });
// Route::get('/beta', function () {
//     return view('beta');
// });
