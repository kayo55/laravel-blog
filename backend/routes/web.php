<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [PostController::class, 'index'])->name('index');

    // Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');

    // POST
    Route::group(['prefix' => 'post', 'as' =>'post.'], function(){
        Route::get('/create', [PostController::class, 'create'])->name('create'); //post.create
        Route::post('/store', [PostController::class, 'store'])->name('store'); //post.store
        Route::get('/{id}/show', [PostController::class, 'show'])->name('show'); // post.show
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit'); // post.edit
        Route::patch('/{id}/update', [PostController::class, 'update'])->name('update'); // post.update
        Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('destroy'); // post.destroy
    });
});
