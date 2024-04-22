<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('articles/{article}',[ArticleController::class,'show'])->name('api.articles.show');//obtiene un articulo en particular
Route::get('articles',[ArticleController::class,'index'])->name('api.articles.index');//obtiene todos los articulos

Route::post('articles',[ArticleController::class,'store'])->name('api.articles.store');//Enviamos un articulo a guardar

Route::patch('articles/{article}', [ArticleController::class, 'update'])->name('api.articles.update');//actualizamos un articulo