<?php namespace Packs\Logger;

use Input;
use Request;
use Viewlog;

class Logger {
	public function writeLog($context, $uid = ""){
		$log = new Viewlog;

		$log->context = $context;
		$log->uid = $uid;

		$log->ip = Request::server("REMOTE_ADDR");

		if(Request::server("HTTP_REFERER")){
			$log->referrer = Request::server("HTTP_REFERER");	
		}
		
		$log->browser = Request::server("HTTP_USER_AGENT");
		$log->queryString = Request::server("QUERY_STRING");

		$log->request = json_encode(Input::all());

		$log->save();
	}
}

?>