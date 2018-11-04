<?php
error_reporting(E_ALL & ~E_WARNING);
require 'core/ClassLoader.php';

new \Core\ClassLoader([ 'src' ]);

$config = new \Core\ConfigIni('src/config/config.ini');

$db = new \Core\DB($config['db']['name']);

$command = $argv[1];

$cmd = [
	'migration' => 'migrate'
];

switch ($command) {
	case NULL:
		echo "Необходимо ввести команду";
		break;
	case '-h':
	case '--help':
		echo "{$cmd['migration']} - запустить миграции";
		break;
	case $cmd['migration']:
		migrate();
		break;
	default:
		echo "Неизвестная команда 😞";
}

echo PHP_EOL;

function migrate() {
	$config = (\Core\ConfigIni::getInstance())['migrate'];

	$migrateDir = __DIR__ . '/src/' . $config['dir'];
	$migrateFileNames = scandir($migrateDir);

	if ($migrateFileNames === false) {
		return;
	}

	$migrateFileNames = array_diff($migrateFileNames, ['..', '.']);

	if (!$migrateFileNames) {
		return;
	}

	$migrateTable = $config['table'];
	$dbConnection = (\Core\DB::getInstance())->getConnection();

	$lastMigrateNumberQuery = "SELECT * FROM $migrateTable ORDER BY number DESC LIMIT 1";

	$lastMigrateNumberResult = $dbConnection->query($lastMigrateNumberQuery);

	if ($lastMigrateNumberResult === false) {
		switch ($dbConnection->lastErrorCode()) {
			case 1:
				$dbConnection->query("
					CREATE TABLE $migrateTable (
						number integer PRIMARY KEY,
						datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
					);
				");

				$lastMigrateNumber = 0;

				break;
			default:
				throw new \Exception($dbConnection->lastErrorMsg());
		}
	} else {
		$lastMigrateNumber = $lastMigrateNumberResult->fetchArray()['number'] ?? 0;
	}

	$migrationFileNames = array_filter($migrateFileNames, function ($value) use ($lastMigrateNumber) {
		$migrationNumber = (int) preg_replace('/migrate(\d+).php/', '${1}', $value);

		return $migrationNumber > $lastMigrateNumber;
	});

	foreach ($migrationFileNames as $migrationFileName) {
		$migrateClassName = $config['dir'] . '\\' . str_replace('.php', '', $migrationFileName);

		try {
			echo "🧠 $migrationFileName" . PHP_EOL;

			(new $migrateClassName)->run();

			echo "👍🏻 $migrationFileName" . PHP_EOL;
		} catch (\Exception $e) {
			echo "❌ {$e->getMessage()}";
		}
	}

}