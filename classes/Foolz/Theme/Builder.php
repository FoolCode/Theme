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
	 * Simple variable to choose an alternative style, which must be handled manually in the code
	 *
	 * @var null
	 */
	protected $style = null;

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
	 * Instance of a props object
	 *
	 * @var  \Foolz\Theme\Props
	 */
	protected $props = null;

    /**
     * Tells whether we're streaming the response
     *
     * @var bool
     */
    protected $streaming = false;

	/**
	 * We need at least a theme
	 *
	 * @param  \Foolz\Theme\Theme  $theme  The theme object creating this builder
	 */
	public function __construct(\Foolz\Theme\Theme $theme)
	{
		$this->theme = $theme;
		$this->param_manager = new ParamManager();
		$this->props = new Props();
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
	 * Set a style
	 *
	 * @param  null|string  $style The key of the style, null for setting no style
	 *
	 * @throws  \OutOfBoundsException  If the style doesn't exist
	 * @return  $this  \Foolz\Theme\Builder
	 */
	public function setStyle($style = null)
	{
		if ($styles = $this->getTheme()->getConfig('extra.styles', false))
		{
			if (isset($styles[$style]))
			{
				$this->style = $style;
				return $this;
			}
		}

		throw new \OutOfBoundsException;
	}

	/**
	 * Returns the style
	 *
	 * @return  null|string  Null if no style is available, string with the key of the style if it is set or the first available style
	 */
	public function getStyle()
	{
		if ($this->style === null)
		{
			if ($styles = $this->getTheme()->getConfig('extra.styles', false))
			{
				return key($styles);
			}
		}

		return $this->style;
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
	 * Returns the props object to manage title, meta etc.
	 *
	 * @return  \Foolz\Theme\Props  The props object
	 */
	public function getProps()
	{
		return $this->props;
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
		if (array_key_exists($name, $this->partials))
		{
			return $this->partials[$name];
		}

		throw new \OutOfBoundsException('No such partial exists.');
	}

	/**
	 * Tells if a partial has already been created
	 *
	 * @param  string  $name  The name of the partial
	 *
	 * @return  bool  True if the partial has been set, false otherwise
	 */
	public function isPartial($name)
	{
		return array_key_exists($name, $this->partials);
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

    /**
     * Tells if we're streaming the building
     *
     * @return bool
     */
    public function isStreaming()
    {
        return $this->streaming;
    }

    /**
     * Shorthand for streaming the layout
     *
     * @return  string                 The content generated
     * @throws  \OutOfBoundsException  If the layout wasn't selected
     */
    public function stream()
    {
        $this->streaming = true;
        $this->getLayout()->toString();
        flush();
        $this->streaming = false;
    }
}
