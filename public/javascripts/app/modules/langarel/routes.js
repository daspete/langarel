app.config(function($routeProvider){
	$routeProvider.when("/app/connector", {
		templateUrl: "app/connector",
		controller: "FBConnectController"
	});

	$routeProvider.when("/tab/like", {
		templateUrl: "tab/like",
		controller: "LikeController"
	});

	$routeProvider.when("/tab/connect", {
		templateUrl: "tab/connect",
		controller: "ConnectController"
	});
	
	$routeProvider.when("/tab/main", {
		templateUrl: "tab/main",
		controller: "MainController"
	});

	$routeProvider.otherwise({
		redirectTo: "/app/connector"
	});
});
