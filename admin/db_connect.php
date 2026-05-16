<?php

$defaults = [
	'db_host' => '127.0.0.1',
	'db_user' => 'root',
	'db_pass' => '',
	'db_name' => 'ADHA_db',
	'db_port' => 3306,
];

$config = $defaults;
$config_file = __DIR__ . '/config.php';
if (is_readable($config_file)) {
	$loaded = include $config_file;
	if (is_array($loaded)) {
		$config = array_merge($config, $loaded);
	}
}

$conn = new mysqli(
	$config['db_host'],
	$config['db_user'],
	$config['db_pass'],
	$config['db_name'],
	(int) $config['db_port']
) or die('Could not connect to mysql: ' . mysqli_connect_error());

$archived_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'archived'");
if ($archived_col && $archived_col->num_rows === 0) {
	$conn->query('ALTER TABLE orders ADD COLUMN archived tinyint(1) NOT NULL DEFAULT 0 AFTER status');
}
