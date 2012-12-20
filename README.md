Foolz PHP Theme system
=======================

A theme system that abuses OOP to give you those features you've always dreamed of.

You will need PHP 5.4 for this to work. You can install it through [Composer](http://getcomposer.org/) and [Packagist](https://packagist.org/packages/foolz/plugin).

## What a mess!

Foolz\Theme works on top of Foolz\Plugin. This means it has built-in support for plugin-like stuff, that you can use or ignore at will. We use it to allow re-skinning and hooking links on the interface.

We created our own Theme system because other theme systems were nothing more than View managers, monolithic and raw.

This package aims to use a multi-level approach to themes, where you can go up and down in the system and build components at the very last moment - by interacting with the theme directly. The structure goes like this:

* Loader
* Theme
* Builder -> Global Parameter Manager
* View -> Local Parameter Manager

From the View you can bubble up to the Loader, grab the global parameters, enter other partials, and build them within the View.

Other features:

* Child themes: Instead of having to duplicate the entire theme directory, you can extend another theme and fallback on its files. You can also extend from an extended theme, with no limit.
* Asset Manager: compile LESS files on the fly.


## Classes

#### Theme

The Theme object abstracts the content of the theme. These will be returned by the Loader, and you will be able to choose which to use.

* __$theme->createBuilder()__

	Returns a new builder. It's used to create the HTML.

* __$theme->getAssetManager()__

	Returns the asset manager. It's used to load and edit the content of the package that the user will download, like stylesheets and images.

#### Builder

The builder is used to create the HTML. It divides the job between layouts and partials, and provides a global parameter manager.

* __$builder->getTheme()__

	Returns the Theme that generated this builder object.

* __$builder->getParamManager()__

	Returns the parameter manager used to hold parameters for the views.

* __$builder->createLayout($view)__

	* string _$view_ - The name of the layout with underscores

	Sets the layout and returns the View object.

* __$builder->createPartial($name, $view)__

	* string _$name_ - A given name for the partial
	* string _$view_ - The name of the layout with underscores

	Sets a partial with the given $name, and returns the View object.

* __$builder->getLayout()__

	Returns the currently set Layout.

* __$builder->getPartial($name)__

	* string _$name_ - The given name of the partial

	Returns the partial with the given name.

* __$builder->build()__

	Builds the entire HTML with the given parameters.

#### Parameter Manager

Parameter managers are used to consistently store variables for use in the theme. The Builder has a global one, and every View has a local one.

* __$pm->reset()__

	Resets the object to its original state.

* __$pm->getParams()__

	Returns an array of parameters.

* __$pm->getParam($key)__

	* string _$key_ - The key for the value

	Returns the value stored with $key.

	__Throws:__ _\OutOfBoundsException_ - If the value was not set

* __$pm->setParam($key, $value)__

	* string _$key_ - The key for the value
	* mixed _$value_ - The value

	Sets a parameter

* __$pm->setParams($array)__

	* array _$array_ - An array of parameters to set

	Takes an array and inserts every single parameter. Doesn't delete previous parameters.

#### View

The View returned by the Builder is usually a custom object extending \Foolz\Theme\View.

__It's compulsory to override the `toString()` method (not the `__toString()` magic method!) in order to output the HTML. This function should output HTML or return a string.__

* __$view->toString()__

	Called when the HTML must be generated. It should contain the classic HTML building logic. `$this` can be used. No manual output buffering is necessary.

* __$view->getType()__

	Returns the type of View, either `layout` or `partial`.

* __$view->getView()__

	Returns the name of the View.

* __$view->getParamManager()__

	Returns the local parameter manager.

* __$view->build()__

	Builds _and caches_ the HTML. Returns the HTML.

* __$view->doBuild()__

	Builds the HTML and stores it in a variable. Rebuilds the HTML every time it's called.

	_CHAINABLE_
