<?php

namespace Foolz\Theme;

class Theme extends \Foolz\Package\Package
{
	protected static $autoloaded = [];

	public function __construct($dir)
	{
		parent::__construct($dir);
		if ( ! in_array(__CLASS__, static::$autoloaded))
		{
			$this->enableAutoloader();
			static::$autoloaded[] = __CLASS__;
		}
		
	}

	/**
	 * Returns a new Builder object
	 *
	 * @return  \Foolz\Theme\Builder  A new instance of the builder
	 */
	public function createBuilder()
	{
		return new Builder($this);
	}

	/**
	 * Returns a new global Asset object
	 *
	 * @return  \Foolz\Theme\AssetManager  A new instance of the AssetManager
	 */
	public function createAssetManager()
	{
		return new AssetManager($this);
	}

	/**
	 * Checks for the existence of a theme to extend and returns the theme
	 *
	 * @return  \Foolz\Theme\Theme     The base theme we're extending with the current
	 * @throws  \OutOfBoundsException  If the theme is not found or no extended theme has been specified
	 */
	public function getExtended()
	{
		$extended = $this->getConfig('extra.extends', null);

		if ($extended === null)
		{
			throw new \OutOfBoundsException('No theme to extend.');
		}

		try
		{
			return $this->getLoader()->get($this->getDirName(), $extended);
		}
		catch (\OutOfBoundsException $e)
		{
			throw new \OutOfBoundsException('No such theme available for extension.');
		}
	}

	/**
	 * Return the namespace of the theme so it can be autoloaded
	 *
	 * @return  boolean|string  The namespace of the theme
	 */
	public function getNamespace()
	{
		$namespaces_array = $this->getConfig('autoload.psr-0', false);

		if ($namespaces_array === false || empty($namespaces_array))
		{
			return false;
		}

		return key($namespaces_array);
	}
}