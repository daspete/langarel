<?php

class UserController extends BaseController {

	///////////////////////////////////////////////////////////////////////////////
	// GET USER OBJECT FROM DB VIA USERID
	public function get($uid){
		$userID = (!$uid) ? Fb::getUserID() : $uid; // if there is no user ID fetch it from the PHP SDK

		if($uid != 0) { // when we have a logged in FB user
			$user = Appuser::where("uid", "=", $uid)->first(); // try to get the user
			if($user) return $user; // when we have this user in the DB return the object
		}

		return false;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	// FETCH USER VIA FB-GRAPH, WRITE IT TO DB AND RETURN THE USER ARRAY
	public function create() {
		if(!Input::has("auth")){
			return Response::json(array("error" => "noAccesss"), 401);
		}
		
		$auth = Input::get("auth");

		$uid = $auth["userID"];
		$accessToken = $auth["accessToken"];
		
		if($accessToken){ Graph::setAccessToken($accessToken); }
			
		// try to get the user
		$fbUser = Graph::get("/".$uid, array("fields" => "id,email,first_name,last_name,name,gender"));
		
		if($fbUser){ // if graph call was successful
			if(!isset($fbUser->email) || $fbUser->email == ""){
				return Response::json(array("error" => "accessTokenError"), 401);
			}
		
			if(!Appuser::where("uid","=", $uid)->count()){ // is user not in DB
				if(!Appuser::where("email","=",$fbUser->email)->count()){ // email is not in DB
					$user = new Appuser;
					$user->uid = $uid;
					$user->vorname = $fbUser->first_name;
					$user->nachname = $fbUser->last_name;
					$user->nickname = $fbUser->name;
					$user->email = $fbUser->email;
					$user->gender = $fbUser->gender;
					$user->save();
				}else{ // mail exists in DB
					$user = Appuser::where("email", "=", $fbUser->email)->first();
				}
			}else{ // uid exists in db
				$user = Appuser::where("uid", "=", $uid)->first();
			}
			
			return Response::json($user->toArray()); // return user as an array
		}

		return Response::json(array("error" => "graphError"), 401); // no graph success return error
	}
}