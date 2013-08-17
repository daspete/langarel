<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request){
	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	ini_set("max_execution_time","3000");

	// set parsedRequest config
	Config::set("parsedRequest", array());

	// set shareimage location
	$shareImage = Config::get("app.webURL").Config::get("app.shareDatas.image");
	Config::set("app.shareDatas.image", $shareImage);

	// set Javascript config
	Config::set("jsConf", array(
		"CSFR_TOKEN" => csrf_token(),
		"appID" => Config::get("app.appID"),
		"permissions" => Config::get("app.appScope"),
		"googleID" => Config::get("app.googleID"),
		"tabURL" => Config::get("app.tabURL"),
		"appURL" => Config::get("app.appURL"),
		"webURL" => Config::get("app.webURL"),
		"shareDatas" => Config::get("app.shareDatas")
	));
});


App::after(function($request, $response){
	//
});



/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
/////////////////////////////////////////////////////////////////////////
// filter which checks for a FB user and store the value in the configs
/////////////////////////////////////////////////////////////////////////
Route::filter('auth.facebook', function(){
	// get the user by the FB User ID and store his value in the config
	Config::set("user", UserController::get());
});


/////////////////////////////////////////////////////////////////////////
// checks if a user is logged in, if not, redirect to FB tab
/////////////////////////////////////////////////////////////////////////
Route::filter('auth.secureTab', function()
{
	if(!Config::get("user"))
		return Redirect::to("redirect/facebookApp");
});




/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});