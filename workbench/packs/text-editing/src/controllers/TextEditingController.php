<?php

class TextEditingController extends \BaseController {

	public function __construct()
    {
        // check the csrf Token
		$this->beforeFilter('serviceCSRF', array('on' => 'post'));
		$this->beforeFilter('serviceCSRF', array('on' => 'delete'));
		$this->beforeFilter('serviceCSRF', array('on' => 'update'));
		$this->beforeFilter('serviceCSRF', array('on' => 'put'));
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return TextEditing::getOverview($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// get the whole input
		$input = Input::all();
		// get the locale
		$locale = $input['locale'] ?: false;
		// get the new content
		$content = $input['content'];

		// try to update the text
		return TextEditing::saveLang($id, $content, $locale);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}