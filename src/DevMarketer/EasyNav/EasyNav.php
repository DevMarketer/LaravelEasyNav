<?php

namespace DevMarketer\EasyNav;

use Illuminate\Http\Request;

class EasyNav
{
	/**
   * The Request store.
   *
   * @var Illuminate\Http\Request
   */
  protected $request;

	/**
	 * Construct a new EasyNav instance
	 *
	 * @param Illuminate\Http\Request 		$request
	 */
	function __construct(Request $request)
	{
		$this->request = $request;
	}

	function dump()
	{
		dd($this->request);
	}
}
