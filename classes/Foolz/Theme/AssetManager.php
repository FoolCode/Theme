<?php

namespace Foolz\Theme;

class AssetManager extends \Foolz\Package\AssetManager
{
	/**
	 * Returns the Package object that created this instance of AssetManager
	 *
	 * @return  \Foolz\Theme\Theme|null  The Package object that created this instance of AssetManager
	 */
	public function getTheme()
	{
		return parent::getPackage();
	}
}