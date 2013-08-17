///////////////////////////////////////////////////////////////////////////////
// Controller: LikeController
/////////////////////////////////////////////////////////////////////////////
// handles the like view
///////////////////////////////////////////////////////////////////////////
app.controller("LikeController", function($rootScope, $scope, $location, $http, FacebookService, RouterService){

});



///////////////////////////////////////////////////////////////////////////////
// Controller: ConnectController
/////////////////////////////////////////////////////////////////////////////
// handles the connect view
///////////////////////////////////////////////////////////////////////////
app.controller("ConnectController", function($rootScope, $scope, $location, $http, FacebookService, RouterService){
	/////////////////////////////////////////////////////////
	// BroadcastReceiver: checkStatus
	//////////////////////////////////////////////////////
	// if the user is connected go straight into main
	///////////////////////////////////////////////////
	$scope.$on("checkStatus", function(e, response){
		if(response.status == "connected"){
			RouterService.route($rootScope.appRoutes.main);
		}
	});
	
	/////////////////////////////////////////////////////////
	// BroadcastReceiver: checkLoginSuccess
	//////////////////////////////////////////////////////
	// if the login was successful go straight to main
	///////////////////////////////////////////////////
	$scope.$on("checkLoginSuccess", function(e, response){
		if(response.status == "connected"){
			RouterService.route($rootScope.appRoutes.main);
		}
	});
	
	// Button functions
	$scope.login = function(){
		FacebookService.login("checkLoginSuccess", $rootScope.CONFIG.scope);
	}
	
	
	// check users facebook status
	FacebookService.status("checkStatus");
});



///////////////////////////////////////////////////////////////////////////////
// Controller: MainController
/////////////////////////////////////////////////////////////////////////////
// handles the main app view
///////////////////////////////////////////////////////////////////////////
app.controller("MainController", function($rootScope, $scope, $location, $http, FacebookService, AuthService, RouterService){
	/////////////////////////////////////////////////////////
	// BroadcastReceiver: checkLogin
	//////////////////////////////////////////////////////
	// handles login state of the user, 
	// start the app, if the user is connected,
	// if not connected, go back to the connector
	///////////////////////////////////////////////////
	$scope.$on("checkLogin", function(e,response){
		if(response.status != "connected"){
			RouterService.route($rootScope.appRoutes.connector);
		}else{
			AuthService.login(response.auth, "mainStart");
		}
	});
	
	////////////////////////////////////////////////////////////
	// BroadcastReceiver: mainStart
	/////////////////////////////////////////////////////////
	// User is logged in and ready to go (through the app)
	//////////////////////////////////////////////////////
	$scope.$on("mainStart", function(e, response){
		console.log($rootScope);
		//TODO:: do, what the app should do with logged in users
	});
	
	// check if user is logged in
	FacebookService.status("checkLogin");
});



///////////////////////////////////////////////////////////////////////////////
// Controller: FBConnectController
/////////////////////////////////////////////////////////////////////////////
// listens to status changes from the FB-JS-API
// and redirects to the appropriate route
///////////////////////////////////////////////////////////////////////////
app.controller("FBConnectController", function($rootScope, $scope, $http, $location, FacebookService, RouterService){
	/////////////////////////////////////////////////////////
	// BroadcastReceiver: fetchUserStatus
	//////////////////////////////////////////////////////
	// checks facebook login status
	// if connected, go straight forward the the 
	// main app, if not connected, go to 
	// the like view or the connect view 
	// (just how it was configured in the config)
	///////////////////////////////////////////////////
	$scope.$on("fetchUserStatus", function(e,response){
		$rootScope.USER.status = response.status;
		if(response.status == "connected"){
			RouterService.route($rootScope.appRoutes.main);
		}else{
			RouterService.route($rootScope.entryPoint);
		}
	});
	
	// check users facebook status
	FacebookService.status("fetchUserStatus");
});