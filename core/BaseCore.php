<?php

namespace core;

/**
 * Class BaseCore
 * @package core
 */
class BaseCore
{
	public static $classMap = array();

	public static $app;

	public static $route;

    /**
     * @param $className
     * @throws \Exception
     */
	public static function autoload($className)
	{
		if(isset(static::$classMap[$className])) {
			$classFile = static::$classMap[$className];

			if(file_exists($classFile)) {
				require $classFile;
			} else {
				throw new \Exception("Unable to find '$className' in file: $classFile. Namespace messing?");
			}
		}

		return;
	}
}