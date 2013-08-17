<?php namespace Packs\Fb;

use Exception;
use Config;
use Appuser;
use Session;
use Helpers;

// check for the curl extension
if (!function_exists('curl_init')) {
  throw new Exception('Facebook needs the CURL PHP extension.');
}
// check for the json extension
if (!function_exists('json_decode')) {
  throw new Exception('Facebook needs the JSON PHP extension.');
}


class Fb {
	protected $userID = null;
	protected $userConnected = false;
	protected $userOnPage = false;
	protected $userLikedPage = false;
	protected $userIsAdmin = false;
	protected $accessToken = null;

	protected $fb = false;

	public $requestArray = array();

	///////////////////////////////////////////////////////////////////////////////////
	// FB API Connect
	///////////////////////////////////////////////////////////////////////////////////
	public function connectFacebook(){
		if(!$this->fb) {
			// init the FB connection with the app id and secret
			$this->fb = new Facebook(array(
				'appId'  => Config::get("app.appID"),
				'secret' => Config::get("app.appSecret")
			));
		}
	}
	

	///////////////////////////////////////////////////////////////////////////////////
	// destroy the FB session
	///////////////////////////////////////////////////////////////////////////////////
	public function destroySession() {
		$this->connectFacebook();
		
		$this->fb->destroySession();
	}


	///////////////////////////////////////////////////////////////////////////////////
	// functions to parse the signed request
	///////////////////////////////////////////////////////////////////////////////////
	public function getSignedRequest(){
		return $this->fb->getSignedRequest();
	}
	public function parseSignedRequest($request){
		return json_decode(json_encode($this->parse_signed_request($request)));
	}


	///////////////////////////////////////////////////////////////////////////////////
	// FB App Connect Link-Creator
	///////////////////////////////////////////////////////////////////////////////////
	public function createLoginURL($redirectURL = false){
		// check if we got a redirect URL otherwise take the tab URL from the config file
		$redirectURL = (!$redirectURL) ? Config::get("app.tabURL") : $redirectURL;

		// generates a login url for the app
		return $this->fb->getLoginUrl(array(
			"scope" => Config::get("app.appScope"),
			"redirect_uri" => $redirectURL,
			"display" => "page"
		));
	}
	

	///////////////////////////////////////////////////////////////////////////////////
	// gets the user data from he fb graph
	///////////////////////////////////////////////////////////////////////////////////
	public function fetchFBUser($userID, $accessToken = false){
		// get the current or the passed acces token
		$accessToken = (!$accessToken) ? $this->getAccessToken : $accessToken;

		// get the user data from the graph
		$fbUser = json_decode(Helpers::curlGet("https://graph.facebook.com/".$userID."?fields=id,email,first_name,last_name,name,gender&access_token=".$accessToken));

		// return the fb data
		if(isset($fbUser->email)){
			return (object)array(
				"uid" => $userID,
				"vorname" => $fbUser->first_name,
				"nachname" => $fbUser->last_name,
				"nickname" => $fbUser->name,
				"email" => $fbUser->email,
				"gender" => $fbUser->gender,
				"fbUser" => 1
			);
		}

		// we got an error
		return false;
	}


	///////////////////////////////////////////////////////////////////////////////////
	// FB-App Standardfunktionen
	///////////////////////////////////////////////////////////////////////////////////
	// returnes the access token
	public function getAccessToken(){
		$this->connectFacebook();
		
		if(!$this->accessToken)
			$this->accessToken = $this->fb->getAccessToken();

		return $this->accessToken;
	}

	// returns the user FB ID
	public function getUserID(){
		$this->connectFacebook();
		
		if(!$this->userID)
			$this->userID = $this->fb->getUser();

		return $this->userID;
	}


	// checks if the user has liked the site (only possible on FB Iframe)
	public function hasLiked(){
		return $this->userLikedPage;
	}

	// true if the user has a connection to the App
	public function isConnected(){
		return $this->userConnected;
	}

	// returns if the user is on the page (only on the FB Tab)
	public function onPage(){
		return $this->userOnPage;
	}

	// returns if the user is a admin of the page (only on the FB Tab)
	public function isAdmin(){
		return $this->userIsAdmin;
	}


	/**********************************************************************
	   Functions from:
	   https://developers.facebook.com/docs/howtos/login/signed-request/
	***********************************************************************/
	private function parse_signed_request($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);

		return $data;
	}

	private function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}