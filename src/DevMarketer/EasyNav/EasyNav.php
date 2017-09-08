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
   * Used to detect chaining.
   *
   * @var bool
   */
  protected $or;

	/**
	 * Stores previous value if chaining.
	 *
	 * @var bool
	 */
	protected $previous;


	/**
	 * Construct a new EasyNav instance
	 *
	 * @param Illuminate\Http\Request 		$request
	 */
	function __construct(Request $request)
	{
		$this->request = $request;
		$this->or = false;
	}

	protected function dump()
	{
		dd($this->request);
	}

	/**
   * returns the active class if the defined segment exists
	 *  in the current request URI
   *
   * @param		string|array 	$slugs
	 * @param		int|array			$segments
	 * @param		string|NULL		$active
   * @return	string
   */
  public function hasSegment($slugs, $segments = 1, $active = NULL)
  {
		$this->setActive($active);
    $segments = (!is_array($segments) ? [$segments] : $segments);
    $slugs = (!is_array($slugs) ? [$slugs] : $slugs);
		foreach ($slugs as $slug) {
      foreach ($segments as $segment) {
        if ($this->request->segment($segment) == $slug) return $this->active;
      }
		}
	  return '';
  }

	/**
	 * Alias to $this->hasSegment()
	 *
	 * @param		string|array 	$slugs
	 * @param		int|array			$segments
	 * @param		string|NULL		$active
	 * @return	string
	 */
	public function isSegment($slugs, $segments = 1, $active = NULL)
	{
		$this->hasSegment($slugs, $segments, $active);
	}

	/**
 	 * Receives a named route and returns true or false depending
	 *  if the current URL is equal to the named route provided.
	 *
	 * @param 	string				$route
	 * @param 	string|NULL		$active
	 * @return	string
	 */
	public function isRoute($route, $active = NULL)
	{
		$this->setActive($active);
		return ($this->request->routeIs($route) ? $this->active : '');
	}

	/**
 	 * Checks if current page is one of a specified resouce
	 *  provided in the function. Also accepts a prefix or strict
	 *  mode to optionally prevent false-positives
	 *
	 * @param 	string				$resource
	 * @param 	string|NULL		$prefix
	 * @param		string 				$active
	 * @param 	bool					$strict
	 * @return	string
	 */
	public function isResource($resource, $prefix = NULL, $active = NULL, $strict = false)
	{
		$this->setActive($active);
		if ($prefix && is_string($prefix)) {
			$prefix = str_replace('.', '/', $prefix);
			$search = trim($prefix,'/').'/'.trim($resource, '/');
		} else {
			$search = trim($resource, '/');
		}
		return ($this->pathContains($search, $strict) ? $this->active : '');
	}

	/**
	 * This is basically an exposed (public) alias to $this->pathContains()
	 *  which checks a string inside the path
	 *
	 * @param 	string				$route
	 * @param 	string|NULL		$active
	 * @return	string
	 */
	public function urlDoesContain($search, $active = NULL, $strict = false)
	{
		$this->setActive($active);
		return ($this->pathContains($search, $strict) ? $this->active : '');
	}

	/**
	 * Alias for $this->urlDoesContain() because URL is often spelled URI by mistake
	 *
	 * @param 	string				$route
	 * @param 	string|NULL		$active
	 * @return	string
	 */
	public function uriDoesContain($search, $active = NULL, $strict = false)
	{
		return $this->urlDoesContain($search, $active, $strict);
	}



	/**
	 * Does a needle in haystack search using the path (what comes after)
	 *  main domain and TLD.
	 *
	 * @param 	string			$route
	 * @param		bool 				$strict  // TRUE indicates that $needle must be found at the start of the path
	 * @return	bool
	 */
	private function pathContains($needle, $strict = false)
	{
		$needle = trim($needle, ' \\/'); //cleans up the needle in case someone passes in starting or trailing slashes
		$haystack = $this->request->path();
		$existance = strpos($haystack, $needle);
		if ($existance !== false) {
			if ($strict) {
				return $existance === 0 ? true : false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
 	 * Sets the active class to the value in the config file if
	 *  no value is passed with the function.
	 *
	 * @param 	string|NULL		$active
	 * @return	$this
	 */
	private function setActive($active)
	{
		if ($active) {
			return $this->active = $active;
		} else {
			return $this->active = config('easynav.default_class');
		}
	}
}
