<?php

namespace Foolz\Theme;

/**
 * Automates loading of themes
 *
 * @author   Foolz <support@foolz.us>
 * @package  Foolz\Theme
 * @license  http://www.apache.org/licenses/LICENSE-2.0.html Apache License 2.0
 */
class Loader extends \Foolz\Package\Loader
{
	/**
	 * The type of package in use. Can be in example 'theme' or 'theme'
	 * Override this to change type of package
	 *
	 * @var  string
	 */
	protected $type_name = 'theme';

	/**
	 * The class into which the resulting objects are created.
	 * Override this, in example Foolz\Theme\Theme or Foolz\Theme\Theme
	 *
	 * @var  string
	 */
	protected $type_class = 'Foolz\Theme\Theme';

	/**
	 * Path to public directory where assets are copied
	 *
	 * @var string|string
	 */
	protected $public_dir = null;

	/**
	 * URL that points to the public directory
	 *
	 * @var string|null
	 */
	protected $base_url = null;

	/**
	 * Creates or returns a named instance of Loader
	 *
	 * @param   string  $instance  The name of the instance to use or create
	 *
	 * @return  \Foolz\Theme\Loader
	 */
	public static function forge($instance = 'default')
	{
		return parent::forge($instance);
	}

	/**
	 * Gets all the themes or the themes from the directory
	 *
	 * @param   null|string  $dir_name  if specified it gets only a group of themes
	 *
	 * @return  \Foolz\Theme\Theme[]    All the themes or the themes in the directory
	 * @throws  \OutOfBoundsException   If there isn't such a $dir_name set
	 */
	public function getAll($dir_name = null)
	{
		return parent::getAll($dir_name);
	}

	/**
	 * Gets a single theme object
	 *
	 * @param   string  $dir_name  The directory name where to find the theme
	 * @param   string  $slug      The slug of the theme
	 *
	 * @return  \Foolz\Theme\Theme
	 * @throws  \OutOfBoundsException  if the theme doesn't exist
	 */
	public function get($dir_name, $slug)
	{
		return parent::get($dir_name, $slug);
	}
}