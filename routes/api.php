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
        Route::post('delete-product-services', 'ProductServiceApiController@delete_product_services');
        Route::get('details-product-services', 'ProductServiceApiController@details_product_services');
        Route::post('save-lead', 'LeadApiController@save_lead');
        Route::post('update-lead', 'LeadApiController@update_lead');
        Route::post('delete-lead', 'LeadApiController@delete_lead');
        Route::post('update-lead-status', 'LeadApiController@update_lead_status');
        Route::post('delete-lead-document', 'LeadApiController@delete_lead_document');
        Route::get('lead-details', 'LeadApiController@lead_details');

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

        // Company User
        Route::get('company-user-list', 'CompanyUserApiController@company_user_list');
        Route::post('save-company-user', 'CompanyUserApiController@save_company_user');
        Route::post('update-company-user', 'CompanyUserApiController@update_company_user');
        Route::post('delete-company-user', 'CompanyUserApiController@delete_company_user');

        // Dashboard
        Route::get('lead-stage-count', 'DashboardApiController@lead_stage_count');
        Route::get('dashboard-lead-list', 'DashboardApiController@dashboard_lead_list');

        //Country List
        Route::get('country-list', 'CountryApiController@country_list');

    });
});
