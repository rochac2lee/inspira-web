<?php

use App\Http\Controllers\api\ApiHubController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/categorias',[ApiHubController::class, 'categorias']);
Route::post('/disciplinas',[ApiHubController::class, 'disciplinas']);
