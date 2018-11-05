<?php
error_reporting(E_ALL & ~E_WARNING);
require 'core/ClassLoader.php';

new \Core\ClassLoader([ 'src' ]);

$config = new \Core\ConfigIni('src/config/config.ini');

$db = new \Core\DB($config['db']['name']);

try {
	$dispatcher = new \Core\Dispatcher();

	echo $dispatcher->handle();
} catch (\Exception $e) {
	echo new \Core\Response($e->getMessage(), $e->getCode() | 500);
}