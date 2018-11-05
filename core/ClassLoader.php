<?php

namespace Core;

class ClassLoader
{
	private static $_dirs;

	public function __construct(array $dirs = [])
	{
		self::$_dirs = $dirs;
		spl_autoload_register('self::autoLoad');
	}

	public static function autoLoad($class) : bool
	{
		$dirs = self::$_dirs;
		$dirs[] = __DIR__ . '/..';

		$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

		foreach ($dirs as $start) {
			$file = $start . DIRECTORY_SEPARATOR . $fileName;

			if (self::loadFile($file)) {
				return true;
			}
		}

		throw new Exception("Не получается загрузить класс $class");
	}

	private static function loadFile($file) : bool
	{
		if (file_exists($file)) {
			require_once $file;

			return TRUE;
		}
		return FALSE;
	}
}