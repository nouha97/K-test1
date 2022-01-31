<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth/facebook', 'middleware' => 'auth'], function () {
    Route::get('/', [\App\Http\Controllers\TestFbController::class, 'redirectToProvider'])->name('fb.login');
    Route::get('/callback', [\App\Http\Controllers\TestFbController::class, 'handleProviderCallback']);

});

Route::group(['prefix' => '/facebook', 'middleware' => 'auth'], function () {
Route::get('/', [App\Http\Controllers\TestFbController::class, 'index'])->name('fb.index');
Route::get('/MyPages', [App\Http\Controllers\TestFbController::class, 'indexPages'])->name('fb.indexPages');
Route::get('/{id}/MyPage/{access}', [App\Http\Controllers\TestFbController::class, 'indexPosts'])->name('fb.indexPagePosts');
Route::get('/{id}/createPost/{access}', [App\Http\Controllers\TestFbController::class, 'createPost'])->name('fb.createPost');
Route::post('/{id}/savePost/{access}', [App\Http\Controllers\TestFbController::class, 'savePost'])->name('fb.savePost');
Route::get('/{id}/deletePost/{access}/{idPage}', [App\Http\Controllers\TestFbController::class, 'DeletePost'])->name('fb.delete');
});

