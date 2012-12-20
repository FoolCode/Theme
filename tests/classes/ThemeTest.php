<?php

use Foolz\Theme\Theme as Theme;
use Foolz\Theme\Loader as Loader;

class ThemeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @return  \Foolz\Theme\Loader
	 */
	public function load()
	{
		$loader = Loader::forge();
		$loader->addDir('themes', __DIR__.'/../mock/');
		return $loader;
	}

	/**
	 * @return  \Foolz\Theme\Theme
	 */
	public function theme()
	{
		$loader = $this->load();
		return $loader->get('themes', 'foolz/foolfake-theme-fake');
	}


	public function testTheme()
	{
		$this->assertInstanceOf('Foolz\Theme\Theme', $this->theme());
	}

	public function testCreateBuilder()
	{
		$this->assertInstanceOf('Foolz\Theme\Builder', $this->theme()->createBuilder());
	}

	public function testCreateAssetManager()
	{
		$this->assertInstanceOf('Foolz\Theme\AssetManager', $this->theme()->createAssetManager());
	}
}