<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('/attachments', 'AttachmentsController@store')->name('attachments.store');
Route::resource('posts', 'PostsController');
