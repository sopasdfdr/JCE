<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Or the view where your Vue app is loaded
})->where('any', '.*');
