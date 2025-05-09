<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1','middleware' => ['cors', 'json']], function () {
    //require __DIR__.'/v2/home.php';
});