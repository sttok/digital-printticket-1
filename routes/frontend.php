<?php

use Illuminate\Support\Facades\Route;

Route::domain('printticket.test')->group(function () {
    Route::get('/login')->name('panel.administrador');
    Route::get('/')->name('inicio.frontend');
    Route::get('/evento/{id}/{titulo}')->name('ver.evento.frontend');
    Route::get('p/{id}/{titulo}')->name('index.pagina');
});