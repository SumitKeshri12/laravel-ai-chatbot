<?php

use Illuminate\Support\Facades\Route;

// API routes should be placed in routes/api.php, not here.
Route::get('/', function () {
    return view('welcome');
});
