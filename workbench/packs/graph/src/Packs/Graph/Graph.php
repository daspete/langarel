<?php namespace Packs\Graph;

use Config;
use Helpers;

class Graph {
	private $accessToken = null;
	private $graphURL = "https://graph.facebook.com";
	
	/////////////////////////////////////////////////////////
	// set accesstoken for the fb graph
	public function setAccessToken($token){
		$this->accessToken = $token;
	}

	/////////////////////////////////////////////////////////
	// get datas from the graph
	public function get($object, $params=array()){
		if($this->accessToken == null){
			$accessToken = "";
		}else{
			$accessToken = "access_token=".$this->accessToken;
		}
		
		if(count($params) == 0){ // no parameters just go through
			$path = $object."?".$accessToken;
		}else{ // add parameters to url
			$paramObject = "";
			foreach($params as $paramKey => $paramValue){
				$paramObject .= "&".$paramKey."=".$paramValue;
			}
			$paramObject = substr($paramObject,1,strlen($paramObject)-1);
			
			$path = $object."?".$paramObject."&".$accessToken;
		}
		//var_dump($this->graphURL.$path);
		// return std:object with the call result
		//$datas = json_decode(Helpers::curlGet($this->graphURL.$path));
		
		return json_decode(Helpers::curlGet($this->graphURL.$path));
	}

	/////////////////////////////////////////////////////////
	// post datas to the graph
	public function post($object, $params=array()){
		$path = $object;

		// without parameters you'll get no call
		if(count($params) == 0){return false;}
		
		// append the accesstoken to the url if there is one
		if($this->accessToken != null){
			$accessToken = "access_token=".$this->accessToken;
			$path .= "?".$accessToken;
		}

		// return std:object with the result of the post
		return json_decode(Helpers::curlPost($this->raphURL.$path, $params));
	}
	
}



