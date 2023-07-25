<?php

use App\Http\Controllers\Controller;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/users', [Controller::class, 'showUser'])->name('users.show');
Route::post('/posts', [Controller::class, 'createPost'])->name('posts.store');
Route::post('/comments', [Controller::class, 'createComment'])->name('comments.store');
Route::delete('/users', [Controller::class, 'deleteUser'])->name('users.destroy');
