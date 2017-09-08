# Laravel EasyNav: Easy Navigation Tools for Laravel

Every time I build another Laravel app, I find myself re-using the same custom Navigation helpers I have been perfecting over the years. These tools allow you to accurately and concisely change the class on navigation elements depending on the current page. Tools like these allow you to have your navigation manage itself, speeding up the development progress, but with the efficiency for long term production use.

## Installation

Installation is straightforward, setup is similar to every other Laravel Package.

#### 1. Install via Composer

Begin by pulling in the package through Composer:

```
composer require devmarketer/easynav
```

#### 2. Define the Service Provider and alias

Next we need to pull in the alias and service providers.

**Note:** This package supports the new _auto-discovery_ features of Laravel 5.5, so if you are working on a Laravel 5.5 project, then your install is complete, you can skip to step 3.

If you are using Laravel 5.0 - 5.4 then you need to add a provider and alias. Inside of your `config/app.php` define a new service provider

```
'providers' => [
	//  other providers

	DevMarketer\EasyNav\EasyNavServiceProvider::class,
];
```

Then we want to define an alias in the same `config/app.php` file.

```
'aliases' => [
	// other aliases

	'Nav' => DevMarketer\EasyNav\EasyNavFacade::class,
];
```

#### 3. Publish Config File (OPTIONAL)

The config file allows you to override default settings of this package to meet your specific needs. It is optional and allows you to set a default active class name to output on active navigational elements. You can override this value in each function, but setting a default makes your code cleaner. It defaults to `"active"`, which is a common class name used by many developers and **supports Bootstrap**. If you are using a framework like **Bulma** then you want to change this value to `"is-active"`. Many CSS BLM frameworks would require `"--active"`. Set this once and forget it.

To generate a config file type this command into your terminal:

```
php artisan vendor:publish --tag=easynav
```

This generates a config file at `config/easynav.php`.

## Usage

This package is easy to use. It provides a handful of helpful functions for navigation. Think of each of these methods as "tools" to define rules for a navigation element. Once you set the _"rule"_ for that navigation element, you don't have to worry about it. If a user visits that page (based on the rule) then it will output a CSS class that you define (generally something like `"active"` or `"is-active"`) to the html element, which will display an active state on your navigation.

##### [IMPORTANT] What this package does NOT do

This does NOT generate HTML for navigation. It simply adds "active" css classes to your HTML elements based on rules you define. You customize what an active menu/navigation link looks like in your CSS.

---

### How These Nav Rules Work

For each navigation element, you will add one of the following "rules" to the `class=" "` field of the HTML element. It is still your responsibility to style the element in your CSS. Many CSS frameworks have already done this for you.

##### Example:

```
Current Url - https://domain.com/posts/laravel-is-awesome
---

<nav>
	<ul class="navigation">
		<li class="{{ Nav::isRoute('home') }}">Home</li>
		<li class="{{ Nav::isResource('posts') }}">Posts</li> 				// This one will render a class of "active" to the <li></li>
		<li class="{{ Nav::hasSegment('about')}}">About</li>
		<li class="{{ Nav::urlContains('contact') }}">Contact</li>
	</ul>
</nav>
```

This example displays how you might interconnect 4 different "rules" to define when a certain navigational element should receive the "active" class.

**REMEMBER: it is still up to you to style the element! This plugin simply adds an active class to the element dynamically based on rules you define."**

Now that you see how this works in practice, lets take a look at each _rule_ individually to understand how to define it.

---

### isRoute() - Matches Named Routes

This rule will mark an element as active if the current page matches a specified named route.

```
Nav::isRoute($routeName, $activeClass = "active")
```

**Parameters:**

- `$routeName` - REQUIRED String  
	This is the named route you wish to match. It must match the values registered in the "name" column when you run `php artisan route:list`
- `$activeClass` - OPTIONAL String  
	This defaults to the value defined under `default_class` in your config file or `"active"` if no config is generated

**Example:**

```
Current Url - https://domain.com/contact
---

Routes File:
	Route::get('contact', 'BaseController@contact')->name('contact');
---

{{ Nav::isRoute('contact') }} 						// returns "active" [1]
{{ Nav::isRoute('contact', 'is-open') }}	// returns "is-open" [2]
{{ Nav::isRoute('about') }}								// returns "" [3]
```

(1) string "contact" matches the named route which is also "contact", so returns default active class  
(2) "contact" matches the named route we are currently on, so returns the active class provided in the second parameter, "is-open"
(3) "about" does not match the current route we are on, which is "contact", so it returns ""

---

### hasSegment() - Matches Defined Segments

This rule will mark an element as active if the string(s) provided match the segment(s) provided. This is great for defining parent elements that you want marked active if any of its' children are also active.

```
Nav::hasSegment($slugs, $segments = 1, $activeClass = "active")
```

**Parameters:**

- `$slug` - REQUIRED | String or Array of Strings  
This is the value that we try to match. If you want to match against several strings, pass an array of strings. Note that this works like an `OR`, `||` clause. If any of them match, it returns true.
- `$segments` - OPTIONAL | Integer or Array of Ints  
This defines which segment to match the `$slug` against. Defaults to first segment if none is given. Use integers (starting at 1) to define segments. Segments values are representative as: `https://domainDoesntCount.com/1/2/3/4`. Due to limitations with Laravel's Request you can only define segments out to the 4th segment. If you want to scan multiple segments, pass an array of integers to check against any of those segments (works like an `OR`, `||` clause).
- `$activeClass` - OPTIONAL | String  
This defaults to the value defined under `default_class` in your config file or `"active"` if no config is generated

**Example:**

```
Current Url - https://domain.com/posts/created-by/devmarketer
---

{{ Nav::hasSegment('posts') }} 																// returns "active" [1]
{{ Nav::hasSegment('posts', 2) }} 														// returns "" [2]
{{ Nav::hasSegment('devmarketer', [2,3], 'is-active') }} 			// returns "active" [3]
{{ Nav::hasSegment(['devmarketer', 'jacurtis'], [2,3]) }} 		// returns "active" [4]
{{ Nav::hasSegment('posts', [2,3]) }} 												// returns "" [5]
```

(1) "posts" is in the first segment (the default segment) so it returns the default active class  
(2) "posts" is not in the second segment so it returns nothing  
(3) checks if "devmarketer" is in the second OR third segments. It is in the 3rd segment, so it returns the active class provided, "is-active"  
(4) check if "devmarketer" or "jacurtis" are in either the 2nd or 3rd segments. "devmarketer" is in 3rd segment, so returns default active class  
(5) checks for "posts" in second or third segments, returns nothing.

**Example 2:**

It only matches whole segments.

```
Current Url - https://domain.com/example/text/segments
---

{{ Nav::hasSegment('example') }}		// returns "active"
{{ Nav::hasSegment('exam') }}				// returns ""
```

In the first one, it works because "example" is a full segment. But the second one does not work because even though "exam" is contained inside of the first segment "example", it doesn't match the entire segment.

If you want to match part of a segment, look into the urlDoesContain rule, which would return true on the second sample even though it is a partial match.

---

### isResource()

**Matches if a URL belongs to a specified resource.**  
This rule will mark an element as active if it is one of a given resource. Pass in the resource name and a prefix (if applicable). This allows you to match `https://domain.com/posts/create`, `https://domain.com/posts/1`, and `https://domain.com/posts/19/edit` with one rule.

```
Nav::isResource($resource, $prefix, $activeClass = "active", $strict)
```

**Parameters:**

- `$resource` - REQUIRED | String  
The resource that you want to match. Must be equal to the value you provide in `Route::resource()` (but using this helper is not required or this to work). The resource in a url like `/posts/create` would be "posts" not "post".
- `$prefix` - OPTIONAL | String  
This is the prefix if applicable. For example `/admin/posts/create`, would have a "admin" is the prefix. For deep prefixes like `/admin/manage/posts/create` you could submit _"admin.manage"_ or _"admin/manage"_ as a prefix.
- `$activeClass` - OPTIONAL | String  
This defaults to the value defined under `default_class` in your config file or `"active"` if no config is generated
- `$strict` - OPTIONAL | Boolean  
If set to **TRUE**, strict mode will be enabled, requiring that the search for this resource is at the beginning of the path. (See examples for more clarification)

**Example 1:**

```
Current Url - https://domain.com/posts/1/edit
---

{{ Nav::isResource('posts') }} 									// returns "active" [1]
{{ Nav::isResource('posts', NULL, 'is-open') }}	// returns "is-open" [2]
{{ Nav::isResource('users') }}									// returns "" [3]
```

(1) the current URL is of the "posts" resource, so returns default active class  
(2) current URL is of the "posts" resource, so returns the active class specified in third parameter. Second parameter of NULL tells it that no prefix exists for this resource.  
(3) Searching for the "users" resource, but the active URL is of the "posts" resource. So returns "".


**Example 2:**

Using prefixes

```
Current Url - https://domain.com/admin/posts/1/edit
---

{{ Nav::isResource('posts') }} 												// returns "active", !IMPORTANT read notes [1]
{{ Nav::isResource('posts', 'admin', 'is-active') }}	// returns "is-active" [2]
{{ Nav::isResource('posts', 'admin.manage') }}				// returns "" [3]
```

(1) IMPORTANT just like without the prefix, this still returns the default active class. It still determines that this is a resource of "post". **However** this would also return true on a normal `domain.com/posts` or `domain.com/user/posts`. So if you want all of them to return as active, then do not use a prefix as in this example. But if you only want those with the "admin" prefix or the "users" prefix to return as active, then make sure the prefix is given in the second parameter to avoid these false positives.  
(2) current URL matches the "posts" resource with an "admin" prefix, so the active class provided in the 3rd parameter is returned, "is-active"  
(3) Even though this URL has the "admin" prefix, it will return "" because "admin.manage" or "admin/manage" is required. It doesn't matter if you use slashes or dots to denote layers. But the URL provided does not contain the "manage" layer, so this rule will return as not active.


**Example 3:**

Using Strict Mode can be tricky and confusing, but is very powerful if understood and used appropriately.

```
Current Url - https://domain.com/admin/manage/posts/1/edit
---

{{ Nav::isResource('posts', NULL, NULL, TRUE) }} 		  			// returns "" [1]
{{ Nav::isResource('posts', 'manage', 'is-active', TRUE) }}	// returns "" [2]
{{ Nav::isResource('posts', 'manage', NULL, FALSE) }}				// returns "active" [3]
{{ Nav::isResource('posts', 'admin.manage', NULL, TRUE) }}	// returns "active" [4]
```

(1) returns as inactive, because while it is a "posts" resource, strict mode is enable. Meaning that the prefix has to match _AND_ it has to start at the beginning of the path (the part after the .com/). To enable strict mode, ensure that it is the 4th parameter. You might need to add NULL values, or fill in other values to get to a 4th parameter.  
(2) returns as inactive because _STRICT MODE_ indicates that the prefixes don't match perfectly from the start of the url path. (admin/manage is the strict prefix).  
(3) Exactly like (2) except that _STRICT MODE_ was disabled, meaning that it can now return as active. _STRICT MODE_ is disabled by default, so marking it as `FALSE` was unnecessary but achieves the same results.  
(4) Returns as active because both the resource _AND_ the prefix match _EXACTLY_, with _STRICT MODE_ enabled.

---

### urlDoesContain()

This rule will match any existence of a string inside of the path. Be very careful with method as it can result in false positives if used inappropriately.

```
Nav::urlDoesContain($search, $activeClass = "active", $strict = FALSE)
```

**Parameters:**

- `$search` - REQUIRED | String  
The value to search for inside of the URL, represented as a string.
- `$activeClass` - OPTIONAL | String  
This defaults to the value defined under `default_class` in your config file or `"active"` if no config is generated
- `$strict` - REQUIRED | Boolean  
If set to **TRUE**, strict mode will be enabled, requiring that the search from the beginning of the path. (See examples for more clarification)

**Example:**

```
Current Url - https://domain.com/about/devmarketer/edit
---

{{ Nav::hasSegment('about') }}																// returns "active" [1]
{{ Nav::hasSegment('devm', 'open') }}													// returns "open" [2]
{{ Nav::hasSegment('devmarketer', 'active', TRUE) }} 					// returns "" [3]
{{ Nav::hasSegment('about/devmarketer', NULL, TRUE) }} 				// returns "active" [4]
{{ Nav::hasSegment('about', NULL, TRUE) }}										// returns "" [5]
```

(1) "about" is contained in the URL, so returns as active  
(2) "devm" is contained in the URL (even though its not a full segment). Be careful with this, as it can cause false positives if you are not careful. The active class provided is "open" so that is what is returned.  
(3) "devmarketer" is in the URL, but _STRICT MODE_ is also active and because the search term does not begin at the start of the path, it will return as inactive.  
(4) returns as active because _STRICT MODE_ is true, and "about/devmarketer" is at the start of the path even though the URL extends past the search term.  
(5) returns as active because "about" is contained in the url and with _STRICT MODE_ enabled, it still checks out because "about" is found at the start of the the url.

### Helper Functions

In addition to the `Nav::method()` facade that we learned about, there are some helper functions you can use if you prefer to use helper functions.

You can disable the helper functions if you do not want to load them, by changing the `enable_helper_functions` setting to `FALSE` in the `config/easynav.php` file.

#### `navHasSegment($slugs, $segments, $activeClass)`

This works exactly the same as `Nav::hasSegment()`. See the documentation above about how to use it.

#### `navIsRoute($routeName, $activeClass)`

This works exactly the same as `Nav::isRoute()`. See the documentation above about how to use it.

#### `navIsResource($resource, $prefix, $activeClass, $strictMode)`

This works exactly the same as `Nav::isResource()`. See the documentation above about how to use it.

_Note: there is no helper function for the Nav::urlDoesContain() method. This method is too dangerous to be thrown around with a helper function._

---

## Contribute

I encourage you to contribute to this package to improve it and make it better. Even if you don't feel comfortable with coding or submitting a pull-request (PR), you can still support it by submitting issues with bugs or requesting new features, or simply helping discuss existing issues to give us your opinion and shape the progress of this package.

[Read the full Contribution Guide](https://github.com/DevMarketer/LaraFlash/blob/master/CONTRIBUTING.md)

## Contact

I would love to hear from you. I run the DevMarketer channel on YouTube, where we discuss how to _"Build and Grow Your Next Great Idea"_ please subscribe and check out the videos.

I am always on Twitter, and it is a great way to communicate with me or follow me. [Check me out on Twitter](https://twitter.com/_jacurtis).

You can also email me at hello@jacurtis.com for any other requests.
