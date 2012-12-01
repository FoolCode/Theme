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
	public static function forge(\Foolz\Theme\Builder $builder, $type, $view)
	{
		// get the View object in case it can be found
		echo $class = $builder->getTheme()->getNamespace().'\\'.  ucfirst($type).'\\'.Util::lowercaseToClassName($view);
		if (class_exists($class))
		{
			$new = new $class();
		}
		else
		{
			$new = new static();
		}

		$new->setBuilder($builder);
		$new->setType($type);
		$new->setView($view);
		$new->setParamManager(new ParamManager());
		return $new;
	}

	/**
	 * Set the builder
	 *
	 * @param \Foolz\Theme\Builder $builder
	 * @return \Foolz\Theme\View
	 */
	public function setBuilder(\Foolz\Theme\Builder $builder)
	{
		$this->builder = $builder;
		return $this;
	}

	/**
	 * Returns the Builder that created the View
	 *
	 * @return  \Foolz\Theme\Builder  The Builder object
	 */
	public function getBuilder()
	{
		return $this->builder;
	}

	/**
	 * Set the type of view
	 *
	 * @param  string  $type  The type of view
	 *
	 * @return  \Foolz\Theme\View  The current object
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Returns the type of View
	 *
	 * @return  string  The type of view
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Sets the view
	 * It won't change the view once the View object is created
	 *
	 * @param  string  $view  The name of the view
	 *
	 * @return  \Foolz\Theme\View  The current object
	 */
	public function setView($view)
	{
		$this->view = $view;
		return $this;
	}

	/**
	 * Returns the type of view
	 *
	 * @return  string  The type of view
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * Sets the Parameter Manager
	 *
	 * @param  \Foolz\Theme\ParamManager  $param_manager  The parameter manager
	 *
	 * @return  \Foolz\Theme\View  The current object
	 */
	public function setParamManager(\Foolz\Theme\ParamManager $param_manager)
	{
		$this->param_manager = $param_manager;
		return $this;
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
	 * Return the compiled view
	 *
	 * @return  string  The compiled view
	 */
	public function get()
	{
		if ($this->built === null)
		{
			$this->build();
		}

		return $this->built;
	}

	/**
	 * Compiles the view
	 *
	 * @return  \Foolz\Theme\View  The current object
	 */
	public function build()
	{
		$this->built = $this->doBuild();

		return $this;
	}


	/**
	 * If not extended, it will check if there's a classic view file and return the result
	 *
	 * @return  string  The compiled string
	 * @throws  \OutOfBoundsException  If the view is not found also in the extended themes
	 */
	public function toString()
	{
		// try building with an Event
		/*
		$result = \Foolz\Plugin\Hook::forge('\Foolz\Theme\View::toString.build.'.$this->view)
			->setObject($this)
			->execute()
			->get();

		if ( ! $result instanceof \Foolz\Plugin\Void)
		{
			return $result;
		}
		*/

		$theme = $this->getTheme();

		while (true)
		{
			$file = $theme->getDir().$this->type.DIRECTORY_SEPARATOR.$this->view.'.php';
			if (file_exists($file))
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

			$theme = $theme->getExtended();
		}

		// this should be thrown by $theme->getExtended, but we want to be explicit
		throw new \OutOfBoundsException;
	}
}