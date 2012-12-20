<?php

namespace Foolz\Theme;

class AssetManager
{
	/**
	 * The theme creating this object
	 *
	 * @var  \Foolz\Theme\Theme|null
	 */
	protected $theme = null;

	/**
	 * The directory where the files should be put so they are reachable via an URL
	 *
	 * @var  string
	 */
	protected $public_dir = "";

	/**
	 * The modification time of the public dir
	 *
	 * @var  int
	 */
	protected $public_dir_mtime = 0;

	/**
	 * The base URL where the theme files can be found at
	 *
	 * @var  string
	 */
	protected $base_url = "";

	/**
	 * Create a new instance of the asset manager
	 *
	 * @param  \Foolz\Theme\Theme  $theme       The reference to the theme creating this asset manager
	 * @param  string              $public_dir  The directory where files can be accessed via URL
	 * @param  string              $base_url    The URL that points to the public directory
	 *
	 * @return  \Foolz\Theme\AssetManager
	 */
	public function __construct(\Foolz\Theme\Theme $theme, $public_dir, $base_url)
	{
		$this->theme = $theme;
		$this->public_dir = $public_dir;
		$this->public_dir_mtime = filemtime($this->getPublicDir());
		$this->base_url = $base_url;

		// load the assets
		if ( ! file_exists($this->getPublicDir()))
		{
			$this->loadAssets();
		}

		// reload the assets if the assets directory is more recent
		if (filemtime($this->getTheme()->getDir().'assets') > $this->public_dir_mtime)
		{
			$this->clearAssets();
			$this->loadAssets();
		}
	}

	/**
	 * Returns the Theme object that created this instance of AssetManager
	 *
	 * @return  \Foolz\Theme\Theme|null  The Theme object that created this instance of AssetManager
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Returns the path to the directory where the public files get loaded
	 *
	 * @return  string  The path
	 */
	protected function getPublicDir()
	{
		return $this->public_dir.$this->getTheme()->getConfig('name').'/';
	}

	/**
	 * Returns an URL to the asset being requested
	 *
	 * @param  $path  $path  The relative path to the asset to link to
	 *
	 * @return  string  The full URL to the asset
	 */
	public function getAssetLink($path)
	{
		return $this->base_url.$this->getTheme()->getConfig('name').'/'.$path;
	}

	/**
	 * Loads all the asset files from the theme folder
	 */
	protected function loadAssets()
	{
		copy($this->getTheme()->getDir().'assets', $this->getPublicDir());
	}

	/**
	 * Clears all the files in the public theme directory
	 *
	 * @return  \Foolz\Theme\AssetManager  The current object
	 */
	public function clearAssets()
	{
		static::flushDir($this->getPublicDir());

		return $this;
	}

	/**
	 * Empties a directory
	 *
	 * @param  string  $path  The directory to empty
	 */
	protected static function flushDir($path)
	{
		$fp = opendir($path);

		while (false !== ($file = readdir($fp)))
		{
			// Remove '.', '..'
			if (in_array($file, array('.', '..')))
			{
				continue;
			}

			$filepath = $path.'/'.$file;

			if (is_dir($filepath))
			{
				static::flushDir($filepath);

				// removing dir here won't remove the root dir, just as we want it
				rmdir($filepath);
				continue;
			}
			elseif (is_file($filepath))
			{
				unlink($filepath);
			}
		}

		closedir($fp);
	}
}