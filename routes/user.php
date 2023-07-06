<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Bssam -> should put "user" prefix any route
// like when use api you should put "api" prefix any route



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
