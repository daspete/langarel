<?php namespace Packs\Thumber;


require_once("ThumbLib.inc.php");

class Thumber {
	
	public $image = null;
	
	public function open($file){
		return PhpThumbFactory::create($file);
	}
}















?>