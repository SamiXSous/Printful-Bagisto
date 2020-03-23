<?php

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/printful/', 'SamiXSous\Printful\Http\Controllers\PrintfulController@index')->defaults('_config', [
            'view' => 'printful::default.index'
        ])->name('admin.printful.index');

        Route::post('/printful/new', 'SamiXSous\Printful\Http\Controllers\PrintfulController@saveAPI')->name('admin.printful.new');

        Route::get('/printful/sync', 'SamiXSous\Printful\Http\Controllers\PrintfulController@syncStore')->name('admin.printful.sync');
    });
});

