<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Company
    Route::post('companies/media', 'CompanyApiController@storeMedia')->name('companies.storeMedia');
    Route::apiResource('companies', 'CompanyApiController');

    // Company User
    Route::apiResource('company-users', 'CompanyUserApiController');

    // Lead Channels
    Route::apiResource('lead-channels', 'LeadChannelsApiController');

    // Product Service
    Route::post('product-services/media', 'ProductServiceApiController@storeMedia')->name('product-services.storeMedia');
    Route::apiResource('product-services', 'ProductServiceApiController');

    // Lead Status
    Route::apiResource('lead-statuses', 'LeadStatusApiController');

    // Lead Conversion
    Route::apiResource('lead-conversions', 'LeadConversionApiController');

    // Leads
    Route::apiResource('leads', 'LeadsApiController');
});
