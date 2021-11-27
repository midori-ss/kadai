<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Login;
use App\Http\Livewire\Admin;
use App\Http\Livewire\Student;

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

Route::get('/', function () {
    return redirect()->route('redirects');
});
Route::get('redirects', [Login::class, 'index'])->name('redirects');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('admin', Admin::class)->middleware('auth')->name('admin');
Route::get('student', Student::class)->middleware('auth')->name('student');

Route::post('admin/import', [Admin::class, 'import']);