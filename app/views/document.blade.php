<!DOCTYPE HTML>
<html lang="" data-ng-app="{{ Config::get('app.angularAppModuleName') }}">
	<head>
		{{ HTML::style("stylesheets/app.css") }}	
	</head>
	<body>
		<div class = "app" data-ng-view></div>
		<div id = "fb-root"></div>

		{{-- VENDOR SCRIPT IMPORTS --}}
		{{ HTML::script("javascripts/vendor/modernizr.js") }}
		{{ HTML::script("javascripts/vendor/jquery.js") }}
		{{ HTML::script("javascripts/vendor/underscore.js") }}
		{{ HTML::script("javascripts/vendor/angular.js") }}
		{{ HTML::script("javascripts/vendor/angular/sanitize.js") }}
		{{ HTML::script("//connect.facebook.net/de_DE/all.js") }}

		{{-- BASE FOUNDATION IMPORT --}}
		{{ HTML::script("javascripts/vendor/foundation.js") }} 
	
		{{-- FOUNDATION MODULES (IF THERE IS THE NEED TO) --}}
	
		{{-- APP SCRIPTS --}}
		{{ HTML::script("javascripts/app/modules.js") }}
		{{ HTML::script("javascripts/app/modules/langarel/services.js") }}
		{{ HTML::script("javascripts/app/modules/langarel/controllers.js") }}
		{{ HTML::script("javascripts/app/modules/langarel/directives.js") }}
		{{ HTML::script("javascripts/app/modules/langarel/routes.js") }}
		
		{{-- APP CONFIG --}}
		{{ HTML::script("javascripts/app/config.js") }}
	</body>
</html>
