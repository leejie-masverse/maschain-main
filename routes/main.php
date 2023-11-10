<?php


Route::group([
    'prefix' => '',
], function () {

    Route::get('/', 'Main\MainController@home')->name('main.main.home');

    /*Route::get('/email/test/{id}', 'Storefront\VenueController@email')->name('storefront.venue.email_test');*/
});
