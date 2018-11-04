<?php
require 'core/ClassLoader.php';

new \Core\ClassLoader([ 'src' ]);

$config = new \Core\ConfigIni('src/config/config.ini');