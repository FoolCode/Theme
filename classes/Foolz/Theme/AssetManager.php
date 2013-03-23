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
	 *
	 * @return  \Foolz\Theme\AssetManager
	 */
	public function __construct(\Foolz\Theme\Theme $theme)
	{
		$this->theme = $theme;
		$this->public_dir = $this->getTheme()->getLoader()->getPublicDir();
		$this->base_url = $this->getTheme()->getLoader()->getBaseUrl();

		// load the assets
		if ( ! file_exists($this->getPublicDir()))
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
		return $this->public_dir.$this->getTheme()->getConfig('name')
			.'/assets-'.$this->getTheme()->getConfig('version').'/';
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
		$candidate = $this->base_url.$this->getTheme()->getConfig('name')
			.'/assets-'.$this->getTheme()->getConfig('version').'/'.$path;

		if (file_exists($this->getPublicDir().$path))
		{
			return $candidate;
		}

		return $this->getTheme()->getExtended()->getAssetManager()->getAssetLink($path);
	}

	/**
	 * Loads all the asset files from the theme folder
	 */
	protected function loadAssets()
	{
		if ( ! file_exists($this->getPublicDir()))
		{
			mkdir($this->getPublicDir(), 0777, true);
		}

		// damned copy doesn't work with directories
		//copy($this->getTheme()->getDir().'assets', $this->getPublicDir());
		system('cp -R '.$this->getTheme()->getDir().'assets/*'.' '.$this->getPublicDir());
	}

	/**
	 * Clears all the files in the public theme directory
	 *
	 * @return  \Foolz\Theme\AssetManager  The current object
	 */
	public function clearAssets()
	{
		// get it just right out of the assets folder
		if (file_exists($this->public_dir.$this->getTheme()->getConfig('name')))
		{
			static::flushDir($this->public_dir.$this->getTheme()->getConfig('name'));
		}

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