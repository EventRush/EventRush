<?php

use App\Http\Controllers\Admin\AdmDashboardController;
use App\Http\Controllers\Api\BilleterieController;
use App\Http\Controllers\Api\QrCodeController;
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
// Route::get('/paiement/callback', function () {
//     return 'Paiement terminé. Vous pouvez fermer la fenêtre.';
// })->name('paiement.callback');
// Route::get('/paiement/callback', [BilleterieController::class, 'callback'])->name('paiement.callback');