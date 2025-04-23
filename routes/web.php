<?php

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

// Route::get('/paiement/callback', function () {
//     return 'Paiement terminé. Vous pouvez fermer la fenêtre.';
// })->name('paiement.callback');
// Route::get('/paiement/callback', [BilleterieController::class, 'callback'])->name('paiement.callback');