<?php

Route::middleware('guest')->group(function () {
    Route::get('/', 'Mono\WebController@index');
    Route::get('/{service}/verification', 'Apps\ServiceController@verify')->name('service.verify');
});