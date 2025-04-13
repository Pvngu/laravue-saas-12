<?php

use Illuminate\Support\Facades\Route;

// Landing Website
Route::group(['namespace' => 'Front'], function () {

    Route::any('/save-authorize-invoices', ['as' => 'webhook.save-authorize-invoices', 'uses' => 'PaymentWebhookController@saveAuthorizeInvoices']);
    Route::post('/save-stripe-invoices', ['as' => 'webhook.save-stripe-invoices', 'uses' => 'PaymentWebhookController@saveStripeInvoices']);
    Route::post('/save-paypal-invoices', ['as' => 'webhook.save-paypal-invoices', 'uses' => 'PaymentWebhookController@verifyBillingIPN']);
    Route::post('/save-razorpay-invoices', ['as' => 'webhook.save-razorpay-invoices', 'uses' => 'RazorpayWebhookController@saveInvoices']);
    Route::post('/save-paystack-invoices', ['as' => 'webhook.save-paystack-invoices', 'uses' => 'PaymentWebhookController@savePaystackInvoices']);
});
