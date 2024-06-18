<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1'], function () {
    Route::post('login', 'LoginApiController@login');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('lead-channel-list', 'CmsApiController@lead_channel_list');
        Route::get('lead-status-list', 'CmsApiController@lead_status_list');
        Route::get('lead-conversion-list', 'CmsApiController@lead_conversion_list');
        Route::get('lead-list', 'LeadApiController@lead_list');
        Route::get('product-services-list', 'ProductServiceApiController@product_services_list');

        Route::post('save-product-services', 'ProductServiceApiController@save_product_services');
        Route::post('update-product-services', 'ProductServiceApiController@update_product_services');
        Route::get('details-product-services', 'ProductServiceApiController@details_product_services');
        Route::post('save-lead', 'LeadApiController@save_lead');

        // Company
        //Route::post('companies/media', 'CompanyApiController@storeMedia')->name('companies.storeMedia');
        //Route::apiResource('companies', 'CompanyApiController');

        // Company User
        //Route::apiResource('company-users', 'CompanyUserApiController');

        // Lead Channels
        //Route::apiResource('lead-channels', 'LeadChannelsApiController');

        // Product Service
        /**/
        /*Route::post('product-services/media', 'ProductServiceApiController@storeMedia')->name('product-services.storeMedia');
        Route::apiResource('product-services', 'ProductServiceApiController');*/

        // Lead Status
        //Route::apiResource('lead-statuses', 'LeadStatusApiController');

        // Lead Conversion
        //Route::apiResource('lead-conversions', 'LeadConversionApiController');

        // Leads
        Route::apiResource('leads', 'LeadsApiController');
    });
});
