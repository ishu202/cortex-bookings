<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {

    Route::name('adminarea.')
         ->namespace('Cortex\Bookings\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

        // Rooms Routes
        Route::name('rooms.')->prefix('rooms')->group(function () {
            Route::get('/')->name('index')->uses('RoomsController@index');
            Route::get('import')->name('import')->uses('RoomsController@import');
            Route::post('import')->name('hoard')->uses('RoomsController@hoard');
            Route::get('import/logs')->name('import.logs')->uses('RoomsController@importLogs');
            Route::get('create')->name('create')->uses('RoomsController@create');
            Route::post('create')->name('store')->uses('RoomsController@store');
            Route::get('{room}')->name('edit')->uses('RoomsController@edit');
            Route::put('{room}')->name('update')->uses('RoomsController@update');
            Route::get('{room}/logs')->name('logs')->uses('RoomsController@logs');
            Route::delete('{room}')->name('destroy')->uses('RoomsController@destroy');

            Route::name('media.')->prefix('{room}/media')->group(function () {
                Route::get('/')->name('index')->uses('RoomsMediaController@index');
                Route::post('/')->name('store')->uses('RoomsMediaController@store');
                Route::delete('{media}')->name('destroy')->uses('RoomsMediaController@destroy');
            });
        });

        // Bookings Routes
        Route::name('bookings.')->prefix('bookings')->group(function () {
            Route::get('/')->name('index')->uses('BookingsController@index');
            Route::post('list')->name('list')->uses('BookingsController@list');
            Route::post('customers')->name('customers')->uses('BookingsController@customers');
            Route::post('rooms')->name('rooms')->uses('BookingsController@rooms');
            Route::post('store')->name('store')->uses('BookingsController@store');
            Route::put('{booking}')->name('update')->uses('BookingsController@update');
            Route::delete('{booking}')->name('destroy')->uses('BookingsController@destroy');
        });

    });

});


Route::domain('{subdomain}.'.domain())->group(function () {

    Route::name('managerarea.')
         ->namespace('Cortex\Bookings\Http\Controllers\Managerarea')
         ->middleware(['web', 'nohttpcache', 'can:access-managerarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.managerarea') : config('cortex.foundation.route.prefix.managerarea'))->group(function () {

            // Rooms Routes
            Route::name('rooms.')->prefix('rooms')->group(function () {
                Route::get('/')->name('index')->uses('RoomsController@index');
                Route::get('import')->name('import')->uses('RoomsController@import');
                Route::post('import')->name('hoard')->uses('RoomsController@hoard');
                Route::get('import/logs')->name('import.logs')->uses('RoomsController@importLogs');
                Route::get('create')->name('create')->uses('RoomsController@create');
                Route::post('create')->name('store')->uses('RoomsController@store');
                Route::get('{room}')->name('edit')->uses('RoomsController@edit');
                Route::put('{room}')->name('update')->uses('RoomsController@update');
                Route::get('{room}/logs')->name('logs')->uses('RoomsController@logs');
                Route::delete('{room}')->name('destroy')->uses('RoomsController@destroy');

                Route::name('media.')->prefix('{room}/media')->group(function () {
                    Route::get('/')->name('index')->uses('RoomsMediaController@index');
                    Route::post('/')->name('store')->uses('RoomsMediaController@store');
                    Route::delete('{media}')->name('destroy')->uses('RoomsMediaController@destroy');
                });
            });

            // Bookings Routes
            Route::name('bookings.')->prefix('bookings')->group(function () {
                Route::get('/')->name('index')->uses('BookingsController@index');
                Route::post('list')->name('list')->uses('BookingsController@list');
                Route::post('customers')->name('customers')->uses('BookingsController@customers');
                Route::post('rooms')->name('rooms')->uses('BookingsController@rooms');
                Route::post('store')->name('store')->uses('BookingsController@store');
                Route::put('{booking}')->name('update')->uses('BookingsController@update');
                Route::delete('{booking}')->name('destroy')->uses('BookingsController@destroy');
            });

        });
});
