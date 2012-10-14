<?php

namespace Foolz\Theme;

class Theme extends \Foolz\Plugin\Plugin
{

	/**
	 * Returns a new Builder object
	 *
	 * @return  \Foolz\Theme\Builder
	 */
	public function getBuilder()
	{
		return new Builder($this);
	}

	/**
	 * Returns a new global Asset object
	 *
	 * @return  \Foolz\Theme\Builder
	 */
	public function getAsset()
	{
		return new Asset($this);
	}

	/**
	 * Return the namespace of the theme so it can be autoloaded
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