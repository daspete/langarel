<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("javascripts/app/config.js", function(){
	return View::make("config", array(
		"user" => array(
			"datas" => array(),
			"status" => ""
		),
		"config" => array(),
		"request" => (Session::has("parsedRequest")) ? Session::get("parsedRequest") : array()
 	));
});

Route::any('/', function(){
	Session::set("parsedRequest", (Input::has("signed_request")) ? Fb::parseSignedRequest(Input::get("signed_request")) : array());
	return View::make("document");
});

Route::any("app/connector", function(){
	return "Verbinde zu Facebook";
});

Route::group(array("prefix" => "auth"), function(){
	Route::post("create", "UserController@create");
	Route::post("login", "UserController@create");
});


Route::group(array("prefix" => "tab"), function(){
	Route::any("like", function(){ return "like"; });
	Route::any("connect", function(){ return View::make("tab.connect"); });
	Route::any("main", function(){ return "main"; });
});
