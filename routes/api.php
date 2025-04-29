<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* rotas para login */

Route::post('/geral/login',[AuthController::class, 'login']);  //// enviar também parametro TIPO
Route::post('/geral/alterar_permissao',[AuthController::class, 'alteraPermissao']);
Route::post('/geral/login_social',[AuthController::class, 'loginSocial']); //// enviar também parametro TIPO
Route::post('/geral/logout',[AuthController::class, 'logout']);//// enviar também parametro TIPO
Route::post('/geral/refresh',[AuthController::class, 'refresh']); //// enviar também parametro TIPO
Route::post('/geral/usuario',[AuthController::class, 'usuario']); //// enviar também parametro TIPO
Route::post('/geral/permissoes',[AuthController::class, 'permissoes']); //// enviar também parametro TIPO
Route::post('/geral/device_key',[AuthController::class, 'deviceKey']); //// enviar também parametro TIPO
Route::post('/geral/troca_senha',[AuthController::class, 'trocaSenha']); //// enviar também parametro TIPO
