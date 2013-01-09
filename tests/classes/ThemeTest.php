<?php

use Foolz\Theme\Theme as Theme;
use Foolz\Theme\Loader as Loader;

class ThemeTest extends PHPUnit_Framework_TestCase
{
	/**
	 *
	 * @return  \Foolz\Theme\Loader
	 */
	public function load()
	{
		if ( ! file_exists(__DIR__.'/../public'))
		{
			mkdir(__DIR__.'/../public');
		}

		$loader = Loader::forge();
		$loader->addDir('themes', __DIR__.'/../mock/');
		$loader->setBaseUrl("");
		$loader->setPublicDir(__DIR__."/../public");
		return $loader;
	}

	/**
	 *
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

	public function testGetAssetManager()
	{
		$this->assertInstanceOf('Foolz\Theme\AssetManager', $this->theme()->getAssetManager());
	}

	/**
	 * @expectedException        \OutOfBoundsException
	 * @expectedExceptionMessage No theme to extend.
	 */
	public function testGetExtendedThrowsOutOfBoundsNull()
	{
		$loader = $this->load();
		$loader->get('themes', 'foolz/foolfake-theme-fake');
		$this->theme()->getExtended();
	}

	public function testGetNamespace()
	{
		$loader = $this->load();
		$loader->get('themes', 'foolz/foolfake-theme-fake');
		$this->assertSame('Foolz\Foolfake\Theme\Fake', $this->theme()->getNamespace());
	}
}
