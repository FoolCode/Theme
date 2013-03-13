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
			$this->loadAssets();
		}
		else
		{
			//$this->public_dir_mtime = filemtime($this->getPublicDir());

			// reload the assets if the assets directory is more recent
			//if (filemtime($this->getTheme()->getDir().'assets') > $this->public_dir_mtime)
			{
			//	$this->clearAssets();
				$this->loadAssets();
			}
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
	 * Checks if the theme assets exist and returns a boolean
	 *
	 * @return  bool  True if assets exist, false if not.
	 */
	public function assetExists()
	{
		$asset_manager = $this;
		$asset = $asset_manager->public_dir.$asset_manager->getTheme()->getConfig('name').'/';

		do
		{
			return (file_exists($asset));
		}
		while ($asset_manager = $asset_manager->getTheme()->getExtended()->getAssetManager());
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