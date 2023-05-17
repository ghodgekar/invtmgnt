<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Parameter List
Route::get('parameter-list', 'ParameterController@index');
Route::post('parameter-datatable', 'ParameterController@datatable');
Route::post('parameter/store', 'ParameterController@store');
Route::get('parameter/edit/{id}', 'ParameterController@edit');
Route::post('parameter/update', 'ParameterController@update');
Route::post('parameter/active/{id}', 'ParameterController@active');
Route::get('parameter/destroy/{id}', 'ParameterController@destroy');
Route::get('parameter/code/{type}', 'ParameterController@code_list');

// Common Master List
Route::get('common-master-list', 'CommonlistController@index');
Route::post('common-master-datatable', 'CommonlistController@datatable');
Route::post('common-master/store', 'CommonlistController@store');
Route::get('common-master/edit/{id}', 'CommonlistController@edit');
Route::post('common-master/update', 'CommonlistController@update');
Route::post('common-master/active/{id}', 'CommonlistController@active');
Route::get('common-master/destroy/{id}', 'CommonlistController@destroy');

Route::get('common-master/code/{type}', 'CommonlistController@code_list');

// Module List
Route::get('module-list', 'ModuleController@index');
Route::get('parent-module-list', 'ModuleController@get_parent_module');
Route::post('module-datatable', 'ModuleController@datatable');
Route::post('module/store', 'ModuleController@store');
Route::get('module/edit/{id}', 'ModuleController@edit');
Route::post('module/update', 'ModuleController@update');
Route::post('module/active/{id}', 'ModuleController@active');
Route::get('module/destroy/{id}', 'ModuleController@destroy');

// Company List
Route::get('company-list', 'CompanyController@index');
Route::post('company-datatable', 'CompanyController@datatable');
Route::post('company/store', 'CompanyController@store');
Route::get('company/edit/{id}', 'CompanyController@edit');
Route::post('company/update', 'CompanyController@update');
Route::post('company/active/{id}', 'CompanyController@active');
Route::get('company/destroy/{id}', 'CompanyController@destroy');

// Branch List
Route::get('branch-list', 'BranchController@index');
Route::post('branch-datatable', 'BranchController@datatable');
Route::post('branch/store', 'BranchController@store');
Route::get('branch/edit/{id}', 'BranchController@edit');
Route::post('branch/update', 'BranchController@update');
Route::post('branch/active/{id}', 'BranchController@active');
Route::get('branch/destroy/{id}', 'BranchController@destroy');

Route::get('branch/{state}', 'BranchController@getState');

// City List
Route::get('city-list', 'CityController@index');
Route::get('get-state-country/{id}', 'CityController@get_state_country');
Route::post('city-datatable', 'CityController@datatable');
Route::post('city/store', 'CityController@store');
Route::get('city/edit/{id}', 'CityController@edit');
Route::post('city/update', 'CityController@update');
Route::post('city/active/{id}', 'CityController@active');
Route::get('city/destroy/{id}', 'CityController@destroy');

// State List
Route::get('state-list', 'StateController@index');
Route::post('state-datatable', 'StateController@datatable');
Route::post('state/store', 'StateController@store');
Route::get('state/edit/{id}', 'StateController@edit');
Route::post('state/update', 'StateController@update');
Route::post('state/active/{id}', 'StateController@active');
Route::get('state/destroy/{id}', 'StateController@destroy');

// Country List
Route::get('country-list', 'CountryController@index');
Route::post('country-datatable', 'CountryController@datatable');
Route::post('country/store', 'CountryController@store');
Route::get('country/edit/{id}', 'CountryController@edit');
Route::post('country/update', 'CountryController@update');
Route::post('country/active/{id}', 'CountryController@active');
Route::get('country/destroy/{id}', 'CountryController@destroy');

// User List
Route::get('user-list', 'UserController@index');
Route::post('user-datatable', 'UserController@datatable');
Route::post('user/store', 'UserController@store');
Route::get('user/edit/{id}', 'UserController@edit');
Route::post('user/update', 'UserController@update');
Route::post('user/active/{id}', 'UserController@active');
Route::get('user/destroy/{id}', 'UserController@destroy');
Route::get('user-name/{code}', 'UserController@get_username');
Route::post('login', 'UserController@login');

// User Permission List
Route::get('user-permission-list', 'UserpermissionController@index');
Route::post('user-permission-datatable', 'UserpermissionController@datatable');
Route::post('user-permission/store', 'UserpermissionController@store');
Route::get('user-permission/edit/{id}', 'UserpermissionController@edit');
Route::post('user-permission/update', 'UserpermissionController@update');
Route::post('user-permission/active/{id}', 'UserpermissionController@active');
Route::get('user-permission/destroy/{id}', 'UserpermissionController@destroy');

// Tax List
Route::get('tax-list', 'TaxController@index');
Route::post('tax-datatable', 'TaxController@datatable');
Route::post('tax/store', 'TaxController@store');
Route::get('tax/edit/{id}', 'TaxController@edit');
Route::post('tax/update', 'TaxController@update');
Route::post('tax/active/{id}', 'TaxController@active');
Route::get('tax/destroy/{id}', 'TaxController@destroy');

// Item Tax List
Route::get('item-tax-list', 'ItemtaxController@index');
Route::post('item-tax-datatable', 'ItemtaxController@datatable');
Route::post('item-tax/store', 'ItemtaxController@store');
Route::get('item-tax/edit/{id}', 'ItemtaxController@edit');
Route::post('item-tax/update', 'ItemtaxController@update');
Route::post('item-tax/active/{id}', 'ItemtaxController@active');
Route::get('item-tax/destroy/{id}', 'ItemtaxController@destroy');

// Payment Mode List
Route::get('payment-mode-list', 'PaymentmodeController@index');
Route::post('payment-mode-datatable', 'PaymentmodeController@datatable');
Route::post('payment-mode/store', 'PaymentmodeController@store');
Route::get('payment-mode/edit/{id}', 'PaymentmodeController@edit');
Route::post('payment-mode/update', 'PaymentmodeController@update');
Route::post('payment-mode/active/{id}', 'PaymentmodeController@active');
Route::get('payment-mode/destroy/{id}', 'PaymentmodeController@destroy');

// Payment Include/Exclude List
Route::get('payment-include-exclude-list', 'PaymentincludeexcludeController@index');
Route::post('payment-include-exclude-datatable', 'PaymentincludeexcludeController@datatable');
Route::post('payment-include-exclude/store', 'PaymentincludeexcludeController@store');
Route::get('payment-include-exclude/edit/{id}', 'PaymentincludeexcludeController@edit');
Route::post('payment-include-exclude/update', 'PaymentincludeexcludeController@update');
Route::post('payment-include-exclude/active/{id}', 'PaymentincludeexcludeController@active');
Route::get('payment-include-exclude/destroy/{id}', 'PaymentincludeexcludeController@destroy');

// Customer List
Route::get('customer-list', 'CustomerController@index');
Route::post('customer-datatable', 'CustomerController@datatable');
Route::post('customer/store', 'CustomerController@store');
Route::get('customer/edit/{id}', 'CustomerController@edit');
Route::post('customer/update', 'CustomerController@update');
Route::post('customer/active/{id}', 'CustomerController@active');
Route::get('customer/destroy/{id}', 'CustomerController@destroy');

// Vendor List
Route::get('vendor-list', 'VendorController@index');
Route::post('vendor-datatable', 'VendorController@datatable');
Route::post('vendor/store', 'VendorController@store');
Route::get('vendor/edit/{id}', 'VendorController@edit');
Route::post('vendor/update', 'VendorController@update');
Route::post('vendor/active/{id}', 'VendorController@active');
Route::get('vendor/destroy/{id}', 'VendorController@destroy');
Route::get('vendor/{code}', 'VendorController@vendor_code');

// Item Level Scheme List
Route::get('item-level-scheme-list', 'ItemlevelschemeController@index');
Route::post('item-level-scheme-datatable', 'ItemlevelschemeController@datatable');
Route::post('item-level-scheme/store', 'ItemlevelschemeController@store');
Route::get('item-level-scheme/edit/{id}', 'ItemlevelschemeController@edit');
Route::post('item-level-scheme/update', 'ItemlevelschemeController@update');
Route::post('item-level-scheme/active/{id}', 'ItemlevelschemeController@active');
Route::get('item-level-scheme/destroy/{id}', 'ItemlevelschemeController@destroy');

// Item List
Route::get('item-list', 'ItemController@index');
Route::post('item-datatable', 'ItemController@datatable');
Route::post('item/store', 'ItemController@store');
Route::get('item/edit/{id}', 'ItemController@edit');
Route::post('item/update', 'ItemController@update');
Route::post('item/active/{id}', 'ItemController@active');
Route::get('item/destroy/{id}', 'ItemController@destroy');

// Category List
Route::get('category-list', 'CategoryController@index');
Route::post('category-datatable', 'CategoryController@datatable');
Route::post('category/store', 'CategoryController@store');
Route::get('category/edit/{id}', 'CategoryController@edit');
Route::post('category/update', 'CategoryController@update');
Route::post('category/active/{id}', 'CategoryController@active');
Route::get('category/destroy/{id}', 'CategoryController@destroy');

// Sub Category List
Route::get('sub-category-list', 'SubcategoryController@index');
Route::post('sub-category-datatable', 'SubcategoryController@datatable');
Route::post('sub-category/store', 'SubcategoryController@store');
Route::get('sub-category/edit/{id}', 'SubcategoryController@edit');
Route::post('sub-category/update', 'SubcategoryController@update');
Route::post('sub-category/active/{id}', 'SubcategoryController@active');
Route::get('sub-category/destroy/{id}', 'SubcategoryController@destroy');

// Brand List
Route::get('brand-list', 'BrandController@index');
Route::post('brand-datatable', 'BrandController@datatable');
Route::post('brand/store', 'BrandController@store');
Route::get('brand/edit/{id}', 'BrandController@edit');
Route::post('brand/update', 'BrandController@update');
Route::post('brand/active/{id}', 'BrandController@active');
Route::get('brand/destroy/{id}', 'BrandController@destroy');

// Manufracturer List
Route::get('manufracturer-list', 'ManufacturerController@index');
Route::post('manufracturer-datatable', 'ManufacturerController@datatable');
Route::post('manufracturer/store', 'ManufacturerController@store');
Route::get('manufracturer/edit/{id}', 'ManufacturerController@edit');
Route::post('manufracturer/update', 'ManufacturerController@update');
Route::post('manufracturer/active/{id}', 'ManufacturerController@active');
Route::get('manufracturer/destroy/{id}', 'ManufacturerController@destroy');

// Broker List
Route::get('broker-list', 'BrokerController@index');
Route::post('broker-datatable', 'BrokerController@datatable');
Route::post('broker/store', 'BrokerController@store');
Route::get('broker/edit/{id}', 'BrokerController@edit');
Route::post('broker/update', 'BrokerController@update');
Route::post('broker/active/{id}', 'BrokerController@active');
Route::get('broker/destroy/{id}', 'BrokerController@destroy');

// Purchase Entry List
Route::get('purchase-entry-list', 'PurchaseentryController@index');
Route::post('purchase-entry-datatable', 'PurchaseentryController@datatable');
Route::post('purchase-entry/store', 'PurchaseentryController@store');
Route::get('purchase-entry/edit/{id}', 'PurchaseentryController@edit');
Route::post('purchase-entry/update', 'PurchaseentryController@update');
Route::post('purchase-entry/active/{id}', 'PurchaseentryController@active');
Route::get('purchase-entry/destroy/{id}', 'PurchaseentryController@destroy');
Route::get('purchase-entry/barcode/{barcode}', 'PurchaseentryController@barcode');

// Opening Stock List
Route::get('opening-stock-list', 'OpeningstockController@index');
Route::post('opening-stock-datatable', 'OpeningstockController@datatable');
Route::post('opening-stock/store', 'OpeningstockController@store');
Route::get('opening-stock/edit/{id}', 'OpeningstockController@edit');
Route::post('opening-stock/update', 'OpeningstockController@update');
Route::post('opening-stock/active/{id}', 'OpeningstockController@active');
Route::get('opening-stock/destroy/{id}', 'OpeningstockController@destroy');
Route::get('opening-stock/lot-no/{lot}/{barcode}', 'OpeningstockController@lot_no_change');

// Reason List
Route::get('reason-list', 'ReasonController@index');
Route::post('reason-datatable', 'ReasonController@datatable');
Route::post('reason/store', 'ReasonController@store');
Route::get('reason/edit/{id}', 'ReasonController@edit');
Route::post('reason/update', 'ReasonController@update');
Route::post('reason/active/{id}', 'ReasonController@active');
Route::get('reason/destroy/{id}', 'ReasonController@destroy');

Route::get('reason/{code}', 'ReasonController@getReasonByCode');

// Stock Adjustment List
Route::get('stock-adjustment-list', 'StockAdjustmentController@index');
Route::post('stock-adjustment-datatable', 'StockAdjustmentController@datatable');
Route::post('stock-adjustment/store', 'StockAdjustmentController@store');
Route::get('stock-adjustment/edit/{id}', 'StockAdjustmentController@edit');
Route::post('stock-adjustment/update', 'StockAdjustmentController@update');
Route::post('stock-adjustment/active/{id}', 'StockAdjustmentController@active');
Route::get('stock-adjustment/destroy/{id}', 'StockAdjustmentController@destroy');