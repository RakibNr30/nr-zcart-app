<?php
// sites
Route::post('site/massTrash', 'SiteController@massTrash')->name('site.massTrash')->middleware('demoCheck');
Route::post('site/massDestroy', 'SiteController@massDestroy')->name('site.massDestroy')->middleware('demoCheck');
Route::delete('site/emptyTrash', 'SiteController@emptyTrash')->name('site.emptyTrash');
Route::delete('site/{site}/trash', 'SiteController@trash')->name('site.trash'); // site move to trash
Route::get('site/{site}/restore', 'SiteController@restore')->name('site.restore');
Route::resource('site', 'SiteController', ['except' => ['show']]);