<?php

use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->name('login');
Route::view('/apps', 'apps')->name('apps');

