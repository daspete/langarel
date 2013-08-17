//////////////////////////////////////////////////////////////////////////
// Service: RouterService
///////////////////////////////////////////////////////////////////////
// Broadcasts and $location throw errors in combination, so
// we have to use the window location workaround
////////////////////////////////////////////////////////////////////
app.factory("RouterService", function($rootScope, $http, $location, $window){
	return {
		route: function(loc){
			$window.location.href = "#"+loc;
		}
	}
});

//////////////////////////////////////////////////////////////////////////
// Service: FacebookService
///////////////////////////////////////////////////////////////////////
// Holds the facebook JS-API-Functions and expands their
// functionality with event broadcasts
////////////////////////////////////////////////////////////////////
app.factory("FacebookService", function($rootScope, $http, $location){
	return {
		//////////////////////////////////////////////////////////////////////////
		// function: status
		///////////////////////////////////////////////////////////////////////
		// checks the users facebook status and 
		// casts a broadcast with the datas to a specific listener
		////////////////////////////////////////////////////////////////////
		status: function(listener){
			FB.getLoginStatus(function(response){
				$rootScope.$broadcast(listener, {
					status: response.status,
					auth: response.authResponse
				});
			});
		},
		
		//////////////////////////////////////////////////////////////////////////
		// function: login
		///////////////////////////////////////////////////////////////////////
		// logs the user in via the FB-JS-API and 
		// casts a broadcast with the datas to a specific listener
		////////////////////////////////////////////////////////////////////
		login: function(listener,cScope){
			FB.login(function(response){
				$rootScope.$broadcast(listener, {
					status: response.status,
					auth: response.authResponse
				});
			},{scope: cScope});
		}
	}
});

app.factory("AuthService", function($rootScope, $http, $location){
	return {
		//////////////////////////////////////////////////////////////////////////
		// function: login
		///////////////////////////////////////////////////////////////////////
		// backend login for the appuser, casts a broadcast if successful
		// and saves the user datas in the USER field in the APP-Scope
		////////////////////////////////////////////////////////////////////
		login: function(credentials, listener){
			$http.post($rootScope.authRoutes.login,{
				auth: credentials
			}).success(function(response){
				$rootScope.USER.datas = response;
				$rootScope.USER.status = "connected";
				$rootScope.$broadcast(listener);
			}).error(function(response){
				$rootScope.USER.datas = {};
				$rootScope.USER.status = "not_authorized";
				//TODO:: ErrorService
			});
		}
	}
});