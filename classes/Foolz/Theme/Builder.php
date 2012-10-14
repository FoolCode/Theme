<?php

namespace Foolz\Theme;

class Builder
{
	/**
	 * The theme object
	 *
	 * @var  \Foolz\Theme\Theme
	 */
	protected $theme = null;

	/**
	 * The theme extended object to run if the files aren't found in the default one
	 *
	 * @var  \Foolz\Theme\Theme
	 */
	protected $theme_extended = null;

	/**
	 * The selected layout
	 *
	 * @var  string
	 */
	protected $layout = null;

	/**
	 * The array of named partials with their data and object
	 *
	 * @var  array  Associative array with as key the given to the partial
	 */
	protected $partials = array();

	/**
	 * We need at least a theme
	 *
	 * @param  \Foolz\Theme\Theme  $theme
	 */
	public function __construct(\Foolz\Theme\Theme $theme, \Foolz\Theme\Theme $theme_extended = null)
	{
		$this->theme = $theme;
		$this->theme_extended = $theme_extended;
	}

	/**
	 * The theme can be changed halfway as long as we didn't build() yet
	 *
	 * @return  \Foolz\Theme\Builder
	 */
	public function setTheme(\Foolz\Theme\Theme $theme)
	{
		$this->theme = $theme;
		return $this;
	}

	/**
	 * Returns the theme object
	 *
	 * @return	\Foolz\Theme\Theme
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Sets the theme extended
	 *
	 * @param \Foolz\Theme\Theme $theme_extended
	 */
	public function setThemeExtended(\Foolz\Theme\Theme $theme_extended)
	{
		$this->theme_extended = $theme_extended;
	}

	/**
	 * Returns the extended theme object
	 *
	 * @return  \Foolz\Theme\Theme
	 */
	public function getThemeExtended()
	{
		return $this->theme_extended;
	}


	/**
	 * Set the layout
	 *
	 * @param  string  $view  The filename of the layout
	 * @param  array   $data
	 * @param  mixed   $object
	 * @return  \Foolz\Theme\Builder
	 */
	public function setLayout($view, $data = array(), $object = null)
	{
		$this->layout = array(
			'view' => $view,
			'data' => new \Foolz\Theme\Result($data, func_num_args() >= 4 ? $object : new \Foolz\Theme\Void())
		);
		return $this;
	}

	/**
	 * Returns the layout name
	 *
	 * @return  string
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Sets a named
	 *
	 * @param  string  $name    A given name to be able to get the partial in the theme
	 * @param  string  $view    The filename of the partial
	 * @param  array   $data    The array of data to pass to the partial
	 * @param  mixed   $object  Defaults to \Foolz\Theme\Void if no explicit parameter is set
	 */
	public function setPartial($name, $view, $data = array(), $object = null)
	{
		$this->partial[$name] = array(
			'view' => $view,
			'data' => new \Foolz\Theme\Result($data, func_num_args() >= 4 ? $object : new \Foolz\Theme\Void())
		);

		return $this;
	}

	/**
	 * Returns the named partial data
	 *
	 * @param   string  $name
	 * @return  array
	 */
	public function getPartial($name)
	{
		return $this->partial[$name];
	}



	public function build($view, $data)
	{
		foreach ($this->partials as $name => $partial)
		{
			// append to the partial array the 'built' key with the string
			$this->partials[$name]['built'] = $this->doBuild('view', $partial['view'], $partial['data']);
		}

		$this->layout['built'] = $this->doBuild('layout', $this->layout['view'], $this->layout['data']);
	}

	/**
	 *
	 * @param   string  $type
	 * @param   string  $view
	 * @param   array   $data
	 * @param   bool    $extended
	 * @return  string  The compiled result
	 * @throws  \OutOfBoundsException  If the file or Event was not found
	 */
	protected function doBuild($type, $view, $data, $extended = false)
	{
		$theme = $extended ? $this->getTheme() : $this->getExtendedTheme();

		// try building with a class
		if ($this->getTheme()->getNamespace() !== false)
		{
			$class = $this->getTheme()->getNamespace().'\\'.ucfirst($type).'\\'.ucfirst($view);

			if (class_exists($class))
			{
				return (string) new $class($data, $this);
			}
		}

		// try building with an Event
		$result = \Foolz\Plugin\Hook::forge(strtolower(get_class()).'.build.'.strtolower($class))
			->execute()
			->get();

		if ( ! $result instanceof \Foolz\Plugin\Void)
		{
			return $result;
		}

		// try building from the plain file
		$file = $this->getTheme()->getDir().$type.DIRECTORY_SEPARATOR.$view.'.php';
		if ( ! file_exists($file))
		{
			// isolation function
			$function = function()
			{
				ob_start();
				include $this->getTheme()->getDir().$type.DIRECTORY_SEPARATOR.$view.'.php';
				return ob_get_clean();
			};

			return $function();
		}

		// let's see if the extended theme does have the file we're looking for
		if ($this->getExtendedTheme() !== null)
		{
			return $this->doBuild($type, $view, $data);
		}

		throw new \OutOfBoundsException;
	}
}