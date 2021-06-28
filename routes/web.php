<?php
// Common
include('Common.php');

// Front End routes
include('Frontend.php');

// Backoffice routes
include('Backoffice.php');

// Webhooks
// Route::post('webhook/stripe', 'WebhookController@handleStripeCallback'); 		// Stripe
Route::post('stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');
// AJAX routes for get images
// Route::get('order/ajax/taxrate', 'OrderController@ajaxTaxRate')->name('ajax.taxrate');

Route::get('/admin/reviews/add_fake', 'CreateViews@addfake');
Route::post('/admin/reviews/add_fake_reivews', 'CreateViews@add_fake_reivews');

Route::get('/admin/reviews/add_csv', 'CreateViews@add_csv');
Route::post('/admin/reviews/add_csv_reivews', 'CreateViews@add_csv_reivews');

