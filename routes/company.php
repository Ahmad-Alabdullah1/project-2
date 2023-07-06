<?php

use Illuminate\Support\Facades\Route;

//Bssam -> should put "company" prefix any route

Route::get('/', function () {
    return view('welcome');
});
