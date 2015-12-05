<?php

Route::pattern('id', '[0-9]+');
Route::pattern('cantidad', '[0-9]+');

// Clientes
Route::get('cliente/{id?}/{cantidad?}', 'App\\Controllers\\ClienteController@get');
Route::post('cliente', 'App\\Controllers\\ClienteController@post');
Route::delete('cliente/{id}', 'App\\Controllers\\ClienteController@delete');
Route::put('cliente/{id}', 'App\\Controllers\\ClienteController@put');

// Presidentes
Route::get('presidente', 'App\\Controllers\\PresidenteController@get');

// Ciudades
Route::get('ciudad', 'App\\Controllers\\CiudadController@get');
