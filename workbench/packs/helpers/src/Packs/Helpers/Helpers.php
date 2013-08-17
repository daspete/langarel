<?php namespace Packs\Helpers;

use Config;

class Helpers {

	private $startTime;
	private $endTime;
	public $elapsedTime;

	public function makePastTimeString($date){
		$pTime = strtotime($date);
		$eTime = time() - $pTime;

		if ($eTime < 1) { return 'gerade jetzt'; }

		$a = array( 12 * 30 * 24 * 60 * 60  =>  'Jahr',
					30 * 24 * 60 * 60       =>  'Monat',
					24 * 60 * 60            =>  'Tag',
					60 * 60                 =>  'Stunde',
					60                      =>  'Minute',
					1                       =>  'Sekunde'
		);

		foreach ($a as $secs => $str) {
			$d = $eTime / $secs;
			if ($d >= 1) {
				$r = round($d);
				if ($str == "Tag" || $str == "Monat" || $str == "Jahr"){
					return $r . ' ' . $str . ($r > 1 ? 'en' : '');
				}else{
					return $r . ' ' . $str . ($r > 1 ? 'n' : '');	
				}
			}
		}
	}


	public function curlPost($url,$fields=array()){
		$fields_string = "";
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	public function curlGet($url, $auth=array()){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		if(isset($auth["username"])){
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($ch, CURLOPT_USERPWD, $auth["username"].":".$auth["password"]);
		}

		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$content = curl_exec($ch);

		curl_close($ch);

		return $content;
	}


	public function startTimer(){
		$this->startTime = strtotime(date("Y-m-d H:i:s")); 
	}

	public function stopTimer(){
		$this->endTime = strtotime(date("Y-m-d H:i:s"));
	}

	public function getElapsedTime(){
		if($endTime < $startTime){
			$this->stopTimer();
		}

		return $this->endTime - $this->startTime;
	}

	public function angularWrapper($wrapThis){
		return "{{".$wrapThis."}}";
	}
}



