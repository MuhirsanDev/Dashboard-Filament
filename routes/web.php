<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PDFController;
use App\Http\Livewire\Form;
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

Route::get('/', Form::class);
Route::get('/completed', [Form::class, 'completed']);

Route::get('download',[PDFController::class, 'downloadpdf'])->name('download.tes');
Route::get('download/{id}',[PDFController::class, 'userpdf'])->name('download.pdf');

Route::get('downloadimage/{record}',[DownloadController::class, 'download'])->name('download.image');



