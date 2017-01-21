<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::auth();

//route to load the subcounties in a cascading dropdown. Uses Ajax
Route::get('/sub_counties'
	,function(Request $request){
		$county_id = $request::input(['c_id']);            
		$subcounties=DB::table('sub_counties')
		->where('county_id',$county_id)
		->get();

		return \Response::json($subcounties); 
	});
//Route to add and update property likes
Route::get('/user_likes'
	,function(Request $request){
		$property_id = $request::input(['p_id']);            
		$increase_likes=DB::table('properties')
		->where('property_id',$property_id)
		->increment('user_likes',1);
	$likes=DB::table('properties')
	    ->select('user_likes')
		->where('property_id',$property_id)
		->get();

		return \Response::json($likes); 
	});


Route::get('/','PropertyController@homePage');

//Social login routes to redirect and call back
Route::get('social/auth/redirect/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('social/auth/{provider}', 'Auth\AuthController@handleProviderCallback');

//pesapal callback url
Route::get('property/payment-callback', 'AddPropertyController@pesapalCallback');
Route::post('property/pesapallpayment', 'AddPropertyController@proceedToPayment');
Route::get('property/payment-result', 'AddPropertyController@receivePesapalData');

Route::get('/register', 'GenericController@getRegisterCounties');
Route::get('/home', 'HomeController@index');

Route::get('/property/all-properties', 'PropertyController@getAllProperties');
Route::get('/property/houses', 'PropertyController@houses');
Route::get('/property/lands', 'PropertyController@lands');
Route::get('/property/property-details/{id}', 'PropertyController@propertyDetails');
// ->where('id', '[0-9]+');

Route::post('/property/search-houses', 'PropertyController@searchHouses');
Route::post('/property/search-lands', 'PropertyController@searchLands');
Route::post('/property/search-all-properties', 'PropertyController@searchAllProperties');
Route::post('/property/all-quick-search', 'PropertyController@allQuickSearch');
Route::post('/property/land-quick-search', 'PropertyController@landQuickSearch');
Route::post('/property/house-quick-search', 'PropertyController@houseQuickSearch');

Route::get('/subscribe/subscribe-alert', 'AlertController@subscribeAlert');
Route::get('/subscribe/my-alerts', 'AlertController@myAlert');
Route::get('/subscribe/alert-details/{id}', 'AlertController@alertDetails');
// ->where('id', '[0-9]+');
Route::get('/subscribe/edit-alert/{id}', 'AlertController@editAlert');
// ->where('id', '[0-9]+');

Route::post('/subscribe/subscribe-alert', 'AlertController@storeAlert');
Route::post('/subscribe/edit-alert-details', 'AlertController@saveEditedAlertDetails');
Route::post('/subscribe/enable-alert', 'AlertController@enableAlert');
Route::post('/subscribe/disable-alert', 'AlertController@disableAlert');

Route::get('/package/my-packages', 'AlertController@myPackages');
Route::get('/package/package-details/{id}', 'AlertController@packageDetails')
->where('id', '[0-9]+');
Route::get('/package/buy-package', 'AlertController@buyPackages');

Route::get('/add-property', 'AddPropertyController@addOneProperty');
Route::post('/add-property', 'AddPropertyController@storeProperty');
Route::post('/property-location', 'AddPropertyController@propertyLocation');
Route::get('/property/my-properties', 'AddPropertyController@getMyProperties');
Route::get('/property/edit-property/{id}', 'AddPropertyController@editProperty');
// ->where('id', '[0-9]+');
Route::get('/property/delete-property/{id}', 'AddPropertyController@deleteProperty');

Route::post('/property/save-edited-property', 'AddPropertyController@saveEditedProperty');
Route::post('/property/delete-picture', 'AddPropertyController@deletePicture');

Route::get('/contact/', 'GenericController@contact');
Route::get('/about/', 'GenericController@about');
Route::get('/terms-and-conditions/', 'GenericController@termsAndCondition');
Route::get('/privacy/', 'GenericController@privacyPolicy');
Route::get('/selling-buying-tips/', 'GenericController@safeBuying');
Route::get('/package/package-info', 'GenericController@packageInfo');
Route::get('/password/', 'GenericController@passwordReset');

Route::post('/contact-message/', 'GenericController@sendContactEmail');
Route::post('/contact-seller/', 'GenericController@contactSeller');
Route::post('/share-property-via-email/', 'GenericController@shareViaEmail');
Route::post('/share-property-via-sms/', 'GenericController@shareViaSms');

Route::post('/report-property/', 'ReportedController@reportProperty');
Route::get('/test/', 'ReportedController@test');

Route::get('/profile/', 'UserController@userProfile');

Route::post('/update-profile/', 'UserController@updateUserProfile');
Route::post('/change-password/', 'UserController@changeUserPassword');
