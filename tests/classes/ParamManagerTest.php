<?php

use Foolz\Theme\ParamManager as ParamManager;

class ParamManagerTest extends PHPUnit_Framework_TestCase
{
	public function testGetParamsEmpty() 
	{
		$pm = new ParamManager();
		$this->assertEmpty($pm->getParams());
	}

	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testSetGetParamThrowsOutOfBounds()
	{
		$new = new ParamManager();
		$new->getParam('derp');
	}

	public function testGetSetParam()
	{
		$stack = array();
        $this->assertEquals(0, count($stack));
 
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));
 
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
	}

	public function testGetSetParams()
	{
		$arr = array('param1' => 'test', 'param2' => 'testtest');
		$new = new ParamManager();
		$new->setParams($arr);

		$this->assertSame($arr, $new->getParams());
	}
}


