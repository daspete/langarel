<?php namespace Packs\TextEditing;

use Config;
use Response;
use Request;
use Fb;
use Lang;
use File;
use View;

class TextEditing {
	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// checks if the user is an admin or not
	// 
	public function isAdmin() {
		// when text editing is deactivated return false
		if(!Config::get('text-editing::config.active'))
			return false;

		// get the admin IDs from the config
		$adminIDs = Config::get('text-editing::config.admins.fbUIDs');
		// get the admin IPs from the config
		$adminIPs = Config::get('text-editing::config.admins.IPs');

		// get the FB user ID
		Fb::connectFacebook();
		$fbUID = FB::getUserID();

		// check if the fbuid is in the admins array
		if(array_search($fbUID, $adminIDs) !== false || array_search(Request::Server('REMOTE_ADDR'), $adminIPs) !== false)
			return true;
		else 
			return false;
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// function to make the text output of a lang file and wrap it in a text editing info tag 
	// when an admin is connected
	// 
	public function getLang($key, $locale = null, $richText = false) {
		// when no admin is connected return the basic lang text
		if(!$this->isAdmin()) 
			return Lang::get($key, array(), $locale);

		// check which tag should be used todisplay the editable text
		$tag = ($richText === true) ? 'div' : 'span';

		// when a admin is connected wrap the lang text in the editing info tag
		return '<'. $tag .' class="textEditing" data-key="'. $key .'" data-locale="'. $locale .'">'. Lang::get($key, array(), $locale) .'</'. $tag .'>';
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// function to save a new text to a lang file wehn an admin is connected
	// 
	public function saveLang($key, $newContent, $locale = null) {
		// when the connected user is no admin show an error
		if (!$this->isAdmin()) 
			// return 401 status and an error message
			return Response::json(
				array('message' => 'You have no permissions to update the Content of this Element!'),
				401
			);


		// check if the content isn't empty
		if(strlen($newContent) <= 0) 
			// return 401 status and an error message
			return Response::json(
				array('message' => 'The new Content you passed is empty!'),
				423
			);


		// get the namespace, group and item of the key to work with them
		list($namespace, $group, $item) = Lang::parseKey($key);
		// check/set the current locale
		$locale = $locale ?: Lang::getLocale();

		// get all editable lang files
		$editables = Config::get('text-editing::config.editables');
		// check if the group is one of the editable files, when not return an error
		if(array_search($group, $editables) === false)
			// return 401 status and an error message
			return Response::json(
				array('message' => 'This group is not editable!'),
				423
			);

		// build the path where the lang file should be located
		$langPath = app_path() . "/lang/" . $locale . "/" . $group . '.php';

		// when the file exists go on 
		if(File::exists($langPath)) {
			// get the lang array to replace the content
			$langArray = include($langPath);

			// check if the item exits
			if(array_get($langArray, $item)) {
				// set the new value
				array_set($langArray, $item, $newContent);
				
				// saves the new lang file
				return $this->saveLangFile($langArray, $langPath, $newContent);
			}

			// when the item does not exist
			else {
				// stores the current item string
				$currentItemString = '';
				// get the position of the item
				$itemArray = explode('.', $item);

				// loop through the position and extend the array
				foreach ($itemArray as $value) {
					// add a dot on the end if the string isn't empty
					if($currentItemString != '') $currentItemString .= '.';
					// extend the item string
					$currentItemString .= $value;

					// check if the current item string doesn't exists
					if(!array_get($langArray, $currentItemString)) {
						// add the current item
						array_set($langArray, $currentItemString, array());
					}
				}

				// finaly set the new content
				array_set($langArray, $item, $newContent);

				// saves the new lang file
				return $this->saveLangFile($langArray, $langPath, $newContent);
			}
		}


		// when no file is there throw an error
		else 
			// return 404 status and an error message
			return Response::json(
				array('message' => 'The lang file does not exits!'),
				404
			);
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// function to save the new lang file
	// 
	protected function saveLangFile($langArray, $path, $newContent) {
		// build the new lang content
		$newLangContent = "<?php\n\nreturn " . var_export($langArray, true) . ';';

		// try save the new content to the lang file
		if(File::put($path, $newLangContent)) {
			// return 202 status and an message + the new text
			return Response::json(
				array(
					'message' => 'The content was updated',
					'content' => $newContent
				),
				202
			);
		}

		// when there was an error with saving the new content
		else 
			// return 423 status and an error message
			return Response::json(
				array('message' => 'Wasn\'t possible to save the new content!'),
				423
			);
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// function to generate the overview of a lang file
	// 
	public function getOverview($locale) {
		// when the connected user is no admin show an error
		if (!$this->isAdmin())
			// return 401 status and an error message
			return Response::json(
				array('message' => 'You have no permissions!'),
				401
			);

		// get all editable lang files
		$editables = Config::get('text-editing::config.editables');

		// stores the lang file infos
		$langInfos = array();

		// get all editable lang files of the passed locale
		foreach ($editables as $editable) {
			// build the path where the lang file should be located
			$langPath = app_path() . "/lang/" . $locale . "/" . $editable . '.php';

			// when the file exists go on 
			if(File::exists($langPath)) {
				// get the lang array to replace the content
				$langArray = include($langPath);

				// extend the lang infos about the editable lang file
				$langInfos[$editable] = $langArray;
			} 
		}

		// build the view
		return View::make('text-editing::textOverview', array(
			'locale' => $locale,
			'content' => $this->buildLangInfosOverview($langInfos, $locale),
			'conf' => json_encode(array(
				"CSFR_TOKEN" => csrf_token()
			))
		));
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////
	// recursive function to generate the lang info html
	// 
	protected function buildLangInfosOverview($langInfos, $locale = null, $currentKey = "") {
		// define the output
		$html = "";

		// loop through the lang infos and build the overview
		foreach ($langInfos as $key => $value) {
			// add the headline of the current text
			$html .= '<h3>'. $key .':</h3>';

			// if the value is an array continue the recursion
			if(is_array($value)) {
				// build the new key for the recurison
				$recursionKey = ($currentKey == "") ? $key : $currentKey.'.'.$key;
				// continue the recursion
				$html .= '<div class="level">'. $this->buildLangInfosOverview($value, $locale, $recursionKey) .'</div>';
			}

			// otherwise display the text
			else {
				// build the new key fot the text output
				$textOutputKey = ($currentKey == "") ? $key : $currentKey.'.'.$key;
				// display the editable text
				$html .= $this->getLang($textOutputKey, $locale, true);
			}
		}

		// return the html
		return $html;
	}
}