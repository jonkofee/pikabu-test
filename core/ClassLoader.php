<?php

namespace Core;

class ClassLoader
{
	/**
	 * @var array
	 */
	private static $_dirs;

	/**
	 * ClassLoader constructor.
	 * @param array $dirs
	 */
	public function __construct(array $dirs = [])
	{
		self::$_dirs = $dirs;
		spl_autoload_register('self::autoLoad');
	}

	/**
	 * @param string $class
	 * @return bool
	 * @throws Exception
	 */
	public static function autoLoad(string $class) : bool
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

	/**
	 * @param string $file
	 * @return bool
	 */
	private static function loadFile(string $file) : bool
	{
		if (file_exists($file)) {
			require_once $file;

			return TRUE;
		}
		return FALSE;
	}
}