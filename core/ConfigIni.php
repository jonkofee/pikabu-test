<?php
namespace Core;

class ConfigIni extends \ArrayObject
{

	use Singleton;

	public function __construct(string $path)
	{
		$config = parse_ini_file($path, true);

		if ($config === false) {
			throw new Exception("Не удаётся загрузить конфигурационый файл: $path");
		}

		$this->exchangeArray($config);

		self::$instance = $this;
	}

}