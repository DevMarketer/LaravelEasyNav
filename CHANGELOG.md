# Laravel Quick Nav Change Log

All features, bug fixes, and changes in the code base will be updated and documented here with each release.

## Version 1: Official Release

##### 1.0.1 - 1.0.3

**Fixes:**

1. (1.0.4) - Default active class is now "active" instead of "is-active". This now matches the default used by bootstrap framework and matches the statements made in the Readme documentation. [Issue \#3](https://github.com/DevMarketer/LaravelEasyNav/issues/3)
1. (1.0.3) - Removed problematic feature allowing you to selectively enable helper functions. This will be removed until I can find a better way to do it.
1. (1.0.2) - Fixed typos in config files
1. (1.0.1) - Merge config files so that users do not have to export the config if they do not want to.

##### 1.0.0

This update was focused on the core of EasyNav package. This builds out the basic functions of EasyNav and how it works. It provides a basic facade to access various helpful functions focused around returning a specified class name based on the current page the user is on.

This allows a developer to set rules in their navigation partials and then never worry about navigation again.

This update includes the basic structure of the package, includes helper functions and the `Nav` Facade that you can use to configure your app's navigation

**New Features:***

1. Four core methods to use for your navigation and menus
	1. `Nav::hasSegment()` - checks if the current page matches the given value at the given segment;
	1. `Nav::isRoute()` = checks if the current page matches the given named route
	1. `Nav::isResource()` - checks if the current page matches a given resource
	1. `Nav::urlDoesContain()` - searches the current path for a search term you provide
1. Three Helper functions provided
	1. `navHasSegment()` - shortcut of `Nav::hasSegment()`
	1. `navIsRoute()` - shortcut of `Nav::isRoute()`
	1. `navIsResource()` - shortcut of `Nav::isResource`
1. Publishable config file available with the `-tag=easynav`
