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
	 * The selected layout
	 *
	 * @var  \Foolz\Theme\View
	 */
	protected $layout = null;

	/**
	 * The array of named partials with their data and object
	 *
	 * @var  \Foolz\Theme\View[]  Associative array with as key the given name of the partial
	 */
	protected $partials = [];

	/**
	 * "Global" parameter manager, for the entire Builder
	 *
	 * @var \Foolz\Theme\ParamManager
	 */
	protected $param_manager;

	/**
	 * We need at least a theme
	 *
	 * @param  \Foolz\Theme\Theme  $theme  The theme object creating this builder
	 */
	public function __construct(\Foolz\Theme\Theme $theme)
	{
		$this->theme = $theme;
		$this->param_manager = new ParamManager();
	}

	/**
	 * Returns the theme object
	 *
	 * @return	\Foolz\Theme\Theme  Returns the theme object that created this Builder
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Returns the Builder instance of the Parameter Manger
	 *
	 * @return  \Foolz\Theme\ParamManager
	 */
	public function getParamManager()
	{
		return $this->param_manager;
	}

	/**
	 * Set a layout that is going to wrap all the partials
	 *
	 * @param  string  $view  The name of the view file, all lowercase and with words separated with underscore
	 *
	 * @return  \Foolz\Theme\View
	 */
	public function createLayout($view)
	{
		return $this->layout = View::forge($this, 'layout', $view);
	}

	/**
	 * Create a partial view
	 *
	 * @param  string  $name  A given name for the partial
	 * @param  string  $view  The name of the view file, all lowercase and with words separated with underscore
	 *
	 * @return  \Foolz\Theme\View  The View object for the partial
	 */
	public function createPartial($name, $view)
	{
		return $this->partials[$name] = View::forge($this, 'partial', $view);
	}

	/**
	 * Returns the previously created layout
	 *
	 * @return  \Foolz\Theme\View        The recalled layout
	 * @throws  \BadMethodCallException  If the layout wasn't created before
	 */
	public function getLayout()
	{
		if ($this->layout === null)
		{
			throw new \BadMethodCallException('The layout wasn\'t set.');
		}

		return $this->layout;
	}

	/**
	 * Returns a previously created partial
	 *
	 * @param  string  $name  The given name of the partial
	 *
	 * @return  \Foolz\Theme\View      The recalled partial
	 * @throws  \OutOfBoundsException  If the partial wasn't created before
	 */
	public function getPartial($name)
	{
		if (isset($this->partials[$name]))
		{
			return $this->partials[$name];
		}

		throw new \OutOfBoundsException('No such partial exists.');
	}

	/**
	 * Shorthand for building the layout
	 *
	 * @return  string                 The content generated
	 * @throws  \OutOfBoundsException  If the layout wasn't selected
	 */
	public function build()
	{
		return $this->getLayout()->build();
	}
}