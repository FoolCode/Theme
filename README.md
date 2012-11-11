Foolz PHP Theme system
=======================

A theme system that abuses OOP to give those features you always dreamed about.

You will need PHP 5.4 for this to work. You can install it through [Composer](http://getcomposer.org/) and [Packagist](https://packagist.org/packages/foolz/plugin).

## What a mess!

Foolz\Theme works upon Foolz\Plugin. This means it has inbuilt support for plugin-like stuff, and use it if you wish, or just forget about it. We use it plenty to allow re-skinning and hooking links on the interface.

What we disliked about other theme systems is that they were nothing more than View managers, monolithic and raw.

This package aims to use a multi-level approach to themes, where you can go up and down in the system and build components at the very last moment - by interacting with the theme in the theme itself. The structure is the following:

* Loader
* Theme
* Builder -> Global Parameter Manager
* View -> Local Parameter Manager

From the View you can bubble up to the Loader, grab the global parameters, enter other partials, build them within the View.

Other features:

* Child themes: Instead of having to duplicate the entire theme directory, you can extend another and fallback on its files. You can also extend a theme that by itself extends another, without depth limit.
* Asset Manager: compile LESS files on the fly.