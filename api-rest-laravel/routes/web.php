<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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

/* cargado clases */
use App\Http\Middleware\ApiAuthMiddleware;

/* rutas de pruebas */
Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba/{id}', function ($id) {
    return 'Hola desde mi ruta de pruebas'.' '.$id;
});

Route::get('/animales', [App\Http\Controllers\PruebasController::class, 'index']);

Route::get('/test-orm', [App\Http\Controllers\PruebasController::class, 'testOrm']);




/* rutas del api. */
     
    /* rutas de prueba */
    // Route::get('/usuario/pruebas', [App\Http\Controllers\UserController::class, 'pruebas']);
    // Route::get('/post/pruebas', [App\Http\Controllers\PostController::class, 'pruebas']);
    // Route::get('/categoria/pruebas', [App\Http\Controllers\CategoryController::class, 'pruebas']);
 
    /* rutas del controlador de usuarios */
    Route::post('/api/register', [App\Http\Controllers\UserController::class, 'register']);
    Route::post('/api/login', [App\Http\Controllers\UserController::class, 'login']);
    Route::put('/api/user/update', [App\Http\Controllers\UserController::class, 'update']);
    Route::post('/api/user/upload', [App\Http\Controllers\UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
    Route::get('/api/user/avatar/{filename}', [App\Http\Controllers\UserController::class, 'getImage']);
    Route::get('/api/user/detail/{id}', [App\Http\Controllers\UserController::class, 'detail']);
    
    /* rutas del controlador de categorias */
    Route::resource('/api/category', 'App\Http\Controllers\CategoryController');

    /* rutas del controlador de posts o entradas. */
    Route::resource('/api/post', 'App\Http\Controllers\PostController');

    /* ruta del controlador de posts o entradas, para subir la imagen */
    Route::post('/api/post/upload', [App\Http\Controllers\PostController::class, 'upload']);
    
    /* ruta del controlador de posts o entradas, para obtener la imagen guardada */
    Route::get('/api/post/image/{filename}', [App\Http\Controllers\PostController::class, 'getImage']);

    /* ruta para sacar los posts por categoria. */
    Route::get('/api/post/category/{id}', [App\Http\Controllers\PostController::class, 'getPostsByCategory']);
    
    /* ruta para sacar los posts por usuario */
    Route::get('/api/post/user/{id}', [App\Http\Controllers\PostController::class, 'getPostsByUser']);

    
