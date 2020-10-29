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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/estudiante', [App\Http\Controllers\EstudianteController::class, 'index'])->name('estudiante');
Route::post('/asignarcurso', [App\Http\Controllers\EstudianteController::class, 'AsignarCurso'])->name('asignarcurso');
Route::post('/listarcursos', [App\Http\Controllers\EstudianteController::class, 'ListarCursos'])->name('listarcursos');

Route::get('/curso', [App\Http\Controllers\CursoController::class, 'index'])->name('curso');
Route::get('/topcurso', [App\Http\Controllers\CursoController::class, 'GetTopCurso'])->name('topcurso');


Route::apiResource('estudiantes', App\Http\Controllers\APIEstudiantesController::class);
Route::apiResource('cursos', App\Http\Controllers\APICursosController::class);
