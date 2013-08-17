//<script> // just for code highlighting in aptana ;)

app.config(function($httpProvider){
	var responseErrorCatcher = function($location, $q){
		var success = function(response){
			return response;
		};

		var error = function(response){
			if(response.status == 401){
				//TODO:: do anything when an error comes
			}

			return $q.reject(response);
		}

		return function(promise){
			return promise.then(success, error);
		}
	}

	$httpProvider.responseInterceptors.push(responseErrorCatcher);
});

app.run(function($rootScope, $location){
	$rootScope.USER = {{ json_encode($user) }};
	$rootScope.CONFIG = {{ json_encode(Config::get("app.appConfig")) }};
	$rootScope.REQUEST = {{ json_encode($request) }};
	
	$rootScope.authRoutes = {
		login: "auth/login"
	};
	
	$rootScope.appRoutes = {
		connector: "/app/connector",
		like: "/tab/like",
		connect: "/tab/connect",
		main: "/tab/main"
	};
	
	// check if user is on a fb page tab
	if(_.has($rootScope.REQUEST, "page")){
		if($rootScope.CONFIG.userHasToLike && (!$rootScope.REQUEST.page.liked)){
			$rootScope.entryPoint = $rootScope.appRoutes.like;
		}
		
		if($rootScope.CONFIG.userHasToLike && ($rootScope.REQUEST.page.liked)){
			$rootScope.entryPoint = $rootScope.appRoutes.connect;
		}
		
		if(!$rootScope.CONFIG.userHasToLike){
			$rootScope.entryPoint = $rootScope.appRoutes.connect;
		}
	}else{ // user is not on a fb page tab
		$rootScope.entryPoint = $rootScope.appRoutes.connect;	
	}

	FB.init({
	    appId:"{{ Config::get('app.appConfig.appID') }}",
	    status:true,
	    cookie:true,
	    xfbml:true
	});
});