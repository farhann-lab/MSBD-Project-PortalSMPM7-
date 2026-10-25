<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components-preview', function () {
    return view('pages.components-preview');
});
