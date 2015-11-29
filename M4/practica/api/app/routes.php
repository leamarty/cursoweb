<?php

// Clientes
Route::post('cliente', 'App\\Controllers\\ClienteController@post');
Route::get('cliente', 'App\\Controllers\\ClienteController@get');
Route::put('cliente/{id}', 'App\\Controllers\\ClienteController@put');
Route::delete('cliente/{id}', 'App\\Controllers\\ClienteController@delete');

// Presidentes
Route::get('presidente', 'App\\Controllers\\PresidenteController@get');
