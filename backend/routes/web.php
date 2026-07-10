<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return redirect('/admin');
});

Route::get('/api/documentation', function () {
	return view('swagger');
});
