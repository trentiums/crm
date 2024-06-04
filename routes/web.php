<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::post('permissions/parse-csv-import', 'PermissionsController@parseCsvImport')->name('permissions.parseCsvImport');
    Route::post('permissions/process-csv-import', 'PermissionsController@processCsvImport')->name('permissions.processCsvImport');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::post('roles/parse-csv-import', 'RolesController@parseCsvImport')->name('roles.parseCsvImport');
    Route::post('roles/process-csv-import', 'RolesController@processCsvImport')->name('roles.processCsvImport');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/parse-csv-import', 'UsersController@parseCsvImport')->name('users.parseCsvImport');
    Route::post('users/process-csv-import', 'UsersController@processCsvImport')->name('users.processCsvImport');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Company
    Route::delete('companies/destroy', 'CompanyController@massDestroy')->name('companies.massDestroy');
    Route::post('companies/media', 'CompanyController@storeMedia')->name('companies.storeMedia');
    Route::post('companies/ckmedia', 'CompanyController@storeCKEditorImages')->name('companies.storeCKEditorImages');
    Route::post('companies/parse-csv-import', 'CompanyController@parseCsvImport')->name('companies.parseCsvImport');
    Route::post('companies/process-csv-import', 'CompanyController@processCsvImport')->name('companies.processCsvImport');
    Route::resource('companies', 'CompanyController');

    // Company User
    Route::delete('company-users/destroy', 'CompanyUserController@massDestroy')->name('company-users.massDestroy');
    Route::post('company-users/parse-csv-import', 'CompanyUserController@parseCsvImport')->name('company-users.parseCsvImport');
    Route::post('company-users/process-csv-import', 'CompanyUserController@processCsvImport')->name('company-users.processCsvImport');
    Route::resource('company-users', 'CompanyUserController');

    // Lead Channels
    Route::delete('lead-channels/destroy', 'LeadChannelsController@massDestroy')->name('lead-channels.massDestroy');
    Route::post('lead-channels/parse-csv-import', 'LeadChannelsController@parseCsvImport')->name('lead-channels.parseCsvImport');
    Route::post('lead-channels/process-csv-import', 'LeadChannelsController@processCsvImport')->name('lead-channels.processCsvImport');
    Route::resource('lead-channels', 'LeadChannelsController');

    // Product Service
    Route::delete('product-services/destroy', 'ProductServiceController@massDestroy')->name('product-services.massDestroy');
    Route::post('product-services/media', 'ProductServiceController@storeMedia')->name('product-services.storeMedia');
    Route::post('product-services/ckmedia', 'ProductServiceController@storeCKEditorImages')->name('product-services.storeCKEditorImages');
    Route::post('product-services/parse-csv-import', 'ProductServiceController@parseCsvImport')->name('product-services.parseCsvImport');
    Route::post('product-services/process-csv-import', 'ProductServiceController@processCsvImport')->name('product-services.processCsvImport');
    Route::resource('product-services', 'ProductServiceController');

    // Lead Status
    Route::delete('lead-statuses/destroy', 'LeadStatusController@massDestroy')->name('lead-statuses.massDestroy');
    Route::post('lead-statuses/parse-csv-import', 'LeadStatusController@parseCsvImport')->name('lead-statuses.parseCsvImport');
    Route::post('lead-statuses/process-csv-import', 'LeadStatusController@processCsvImport')->name('lead-statuses.processCsvImport');
    Route::resource('lead-statuses', 'LeadStatusController');

    // Lead Conversion
    Route::delete('lead-conversions/destroy', 'LeadConversionController@massDestroy')->name('lead-conversions.massDestroy');
    Route::post('lead-conversions/parse-csv-import', 'LeadConversionController@parseCsvImport')->name('lead-conversions.parseCsvImport');
    Route::post('lead-conversions/process-csv-import', 'LeadConversionController@processCsvImport')->name('lead-conversions.processCsvImport');
    Route::resource('lead-conversions', 'LeadConversionController');

    // Leads
    Route::delete('leads/destroy', 'LeadsController@massDestroy')->name('leads.massDestroy');
    Route::post('leads/parse-csv-import', 'LeadsController@parseCsvImport')->name('leads.parseCsvImport');
    Route::post('leads/process-csv-import', 'LeadsController@processCsvImport')->name('leads.processCsvImport');
    Route::resource('leads', 'LeadsController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
