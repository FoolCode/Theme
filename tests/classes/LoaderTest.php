<?php

use Foolz\Theme\Loader;

class LoaderTest extends PHPUnit_Framework_TestCase
{
    public function testGetTheme()
    {
        $new = Loader::forge('default');
        $new->addDir('test', __DIR__.'/../../tests/mock/');
        $theme = $new->get('test', 'foolz/foolfake-theme-fake');
        $this->assertInstanceOf('Foolz\Theme\Theme', $theme);
    }
}
