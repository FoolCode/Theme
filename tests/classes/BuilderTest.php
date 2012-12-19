<?php

use Foolz\Theme\Loader as Loader;

class BuilderTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		$this->theme()->refreshConfig();
	}

	/**
	 *
	 * @return  \Foolz\Theme\Loader
	 */
	public function load()
	{
		$loader = Loader::forge();
		$loader->addDir('themes', __DIR__.'/../mock/');
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

	/**
	 *
	 * @return  \Foolz\Theme\Builder
	 */
	public function bld()
	{
		return $this->theme()->createBuilder();
	}


	public function testConstruct()
	{
		$this->assertInstanceOf('Foolz\Theme\Builder', $this->bld());
	}

	public function testGetTheme()
	{
		$this->assertInstanceOf('Foolz\Theme\Theme', $this->bld()->getTheme());
	}

	public function testGetParamManager()
	{
		$this->assertInstanceOf('Foolz\Theme\ParamManager', $this->bld()->getParamManager());
	}

	public function testCreateLayout()
	{
		$this->assertInstanceOf('Foolz\Theme\View', $this->bld()->createLayout('this_layout'));
		$this->assertInstanceOf('Foolz\Foolfake\Theme\Fake\Layout\ThisLayout', $this->bld()->createLayout('this_layout'));
	}

	public function testCreatePartial()
	{
		$this->assertInstanceOf('Foolz\Theme\View', $this->bld()->createPartial('one_partial', 'this_partial'));
		$this->assertInstanceOf('Foolz\Foolfake\Theme\Fake\Partial\ThisPartial', $this->bld()->createPartial('one_partial', 'this_partial'));
	}

	public function testBuild()
	{
		$bld = $this->bld();
		$bld->createLayout('ThisLayout');
		$this->assertSame('A fake layout.', $bld->build());
	}
}