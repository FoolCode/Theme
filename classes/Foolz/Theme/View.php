<?php

namespace Foolz\Theme;

class View
{
	/**
	 * Instance of builder that created this View
	 *
	 * @var  \Foolz\Theme\Builder
	 */
	protected $builder;

	/**
	 * The type of view, if partial or layout
	 *
	 * @var  string
	 */
	protected $type;

	/**
	 * The name of the view to recall
	 *
	 * @var  string
	 */
	protected $view;

	/**
	 * The result of the build process
	 *
	 * @var  null|string  Null if not yet built, string otherwise
	 */
	protected $built = null;

	/**
	 * Construct a View, it may be partial or layout
	 *
	 * @param  \Foolz\Theme\Builder  $builder  The Builder object creating this view
	 * @param  string                $type     The type of view, it can be partial or layout
	 * @param  string                $view     The name of the view
	 */
	public function __construct(\Foolz\Theme\Builder $builder, $type, $view)
	{
		$this->builder = $builder;
		$this->type = $type;
		$this->view = $view;
		$this->param_manager = new ParamManager();
	}

	/**
	 * Returns the Builder that created the View
	 *
	 * @return  \Foolz\Theme\Builder
	 */
	public function getBuilder()
	{
		return $this->builder;
	}

	/**
	 * Returns the ParamManager for the View
	 *
	 * @return  \Foolz\Theme\ParamManager  The Parameter Manger to pass variables to the view
	 */
	public function getParamManager()
	{
		return $this->param_manager;
	}

	/**
	 * Return the
	 *
	 * @return type
	 */
	public function get()
	{
		if ($this->built === null)
		{
			$this->build();
		}

		return $this->built;
	}

	public function build()
	{
		$this->built = $this->doBuild();

		return $this;
	}


	protected function doBuild($theme = null)
	{
		if ($theme === null)
		{
			$theme = $this->getBuilder()->getTheme();
		}

		// try building with a class
		if ($this->getTheme()->getNamespace() !== false)
		{
			$class = $this->getTheme()->getNamespace().'\\'.ucfirst($this->type).'\\'.Util::lowercaseToClassName($this->view);

			if (class_exists($class))
			{
				$view_class = new $class($this);

				return $view_class->build();
			}
		}

		// try building with an Event
		$result = \Foolz\Plugin\Hook::forge('\Foolz\Theme\View::build.'.$class)
			->setObject($this)
			->execute()
			->get();

		if ( ! $result instanceof \Foolz\Plugin\Void)
		{
			return $result;
		}

		// try building from the plain file
		$file = $this->getTheme()->getDir().$this->type.DIRECTORY_SEPARATOR.$view.'.php';
		if ( ! file_exists($file))
		{
			// isolation function
			$function = function()
			{
				ob_start();
				include $this->getTheme()->getDir().$this->type.DIRECTORY_SEPARATOR.$this->view.'.php';
				return ob_get_clean();
			};

			$function = $function->bindTo($this);
			return $function();
		}

		// we shouldn't be here unless we're extending a theme

		// let's see if the extended theme does have the file we're looking for
		if ($theme->getExtended() !== null)
		{
			return $this->doBuild($type, $view, $data);
		}

		throw new \OutOfBoundsException;
	}
}