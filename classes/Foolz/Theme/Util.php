<?php

namespace Foolz\Theme;

class Util
{
	/**
	 * Reformats a lowercase string to a class name by splitting on underscores and capitalizing
	 *
	 * @param  string  $class_name  The name of the class, lowercase and with words separated by underscore
	 *
	 * @return  string
	 */
	public static function lowercaseToClassName($class_name)
	{
		$pieces = explode('_', $class_name);

		$result = '';
		foreach ($pieces as $piece)
		{
			$result .= ucfirst($piece);
		}

		return $result;
	}
}