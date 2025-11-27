<?php

use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/registration', RegisterController::class);
