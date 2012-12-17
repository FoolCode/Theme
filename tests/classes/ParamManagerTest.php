<?php

use Foolz\Theme\ParamManager as ParamManager;

class ParamManagerTest extends PHPUnit_Framework_TestCase
{
	public function testGetParamsEmpty() 
	{
		$pm = new ParamManager();
		$this->assertEmpty($pm->getParams());
	}

	public function testGetParamThrows()
	{
		$this->setExpectedException('OutOfBoundsException');
		throw new OutOfBoundsException('OutOfBoundsException', 10);
	}

	public function testGetSetParam()
	{
		$this->assertSame(10, 10);
	}

	public function testGetSetParams()
	{
		$this->assertArrayHasKey('foo', array('foo' => 'bar'));
	}

}


