<?php namespace Packs\Couchdb;

use Helpers;
use Config;

class Couchdb{
	public function getAll($database){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database."/_all_docs");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}

	public function createDB($database){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
		curl_setopt($ch,CURLOPT_PUT, true);
		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}

	public function put($database, $id, $datas){
		$putData = tmpfile();
		fwrite($putData, $datas);
		fseek($putData, 0);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database."/".$id);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
		curl_setopt($ch,CURLOPT_PUT, true);
		curl_setopt($ch, CURLOPT_INFILE, $putData);
		curl_setopt($ch, CURLOPT_INFILESIZE, strlen($datas));
		$content = curl_exec($ch);
		curl_close($ch);
		fclose($putData);

		return $content;
	}

	public function view($database,$design,$view){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database."/_design/".$design."/_view/".$view);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}

	public function tempView($database,$fields,$params=array()){
		$params = implode("&", $params);
		if(strlen($params) != 0){ $params = "?".$params; }
		ob_start();

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database."/_temp_view".$params);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
		$content = curl_exec($ch);
		curl_close($ch);

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function designView($database,$design,$view,$fields,$params=array()){
		$params = implode("&", $params);
		if(strlen($params) != 0){ $params = "?".$params; }
		ob_start();

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$database."/_design/".$design."/_view/".$view.$params);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
		$content = curl_exec($ch);
		curl_close($ch);

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function delete($item){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,"http://".Config::get("app.couchDBAdmin").":".Config::get("app.couchDBPassword")."@".Config::get("app.couchDBHost").":".Config::get("app.couchDBPort")."/".$item);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    $content = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

	    return $content;
	}


}