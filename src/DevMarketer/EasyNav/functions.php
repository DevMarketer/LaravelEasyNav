<?php
if (! function_exists('navHasSegment')) {
  /**
   * Helper function check if URL path contains a segment.
   *
   * @param  string|array     $slugs
   * @param  int|array        $segments
   * @return string
   */
  function navHasSegment($slugs, $segments = 1, $active = NULL)
  {
      $easynav = app('easynav');
      return $easynav->hasSegment($slugs, $segments, $active);
  }
}

if (! function_exists('navIsRoute')) {
	/**
	 * Helper function to check if current page is equal to a
	 *  specified named route
	 *
	 * @param  string			      $route
	 * @param  string|null      $options
	 * @return string
	 */
	function navIsRoute($route, $active = null)
		{
			$easynav = app('easynav');
				return $easynav->isRoute($route, $active);
		}
}

if (! function_exists('navIsResource')) {
	/**
	 * Helper function to check if current page is one of a
	 *  specified resource
	 *
	 * @param  string			      $resource
	 * @param  string|NULL      $prefix
	 * @param  string|NULL			$active
	 * @param  bool							$strict
	 * @return string
	 */
	function navIsResource($resource, $prefix = NULL, $active = NULL, $strict = false)
	{
			$easynav = app('easynav');
			return $easynav->isResource($resource, $prefix, $active, $strict);
	}
}
