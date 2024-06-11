<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1'], function () {
    Route::post('login', 'LoginApiController@login');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('lead-channel-list', 'CmsApiController@lead_channel_list');
        Route::get('lead-status-list', 'CmsApiController@lead_status_list');
        Route::get('lead-conversion-list', 'CmsApiController@lead_conversion_list');
        Route::get('product-services-list', 'CmsApiController@product_services_list');
        Route::get('lead-list', 'LeadApiController@lead_list');



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
});
