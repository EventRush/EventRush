<?php

use App\Http\Controllers\Admin\AdmDashboardController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Dashboard\TestContoller;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('qrcode');
});
Route::get('/code', [QrCodeController::class, 'vue'])->name('codeqr.vue');
Route::post('/codeGenerate', [QrCodeController::class, 'generate'])->name('codeqr');


// Route::get('/admin', [AdmDashboardController::class, ]->name(''));

Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/well', function () {
    return view('well');
})->name('well');


Route::get('/admin/events/index', function () {
    return view('admin.events.index');
})->name('admin.events.index');
Route::get('/admin/users/index', function () {
    return view('admin.users.index');
})->name('admin.users.index');
Route::get('/admin/tickets/index', function () {
    return view('admin.tickets.index');
})->name('admin.tickets.index');


//  ***test

Route::get('/testlogin',[TestContoller::class, 'testLoginForm'])->name('testlogin');
Route::post('/testlogin',[TestContoller::class, 'testLogin'])->name('testLogin');
Route::post('/testlogout',[TestContoller::class, 'testLogout'])->name('testLogout');
Route::middleware(['web.auth'])->group(function () {
    Route::get('/dashboard', [TestContoller::class, 'index'])->name('testdashboard');
    Route::get('/dashboard_2', [TestContoller::class, 'index_2'])->name('testdashboard_2');

});








