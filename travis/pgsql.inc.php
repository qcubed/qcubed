<?php

	/*
	 * Configure the pgsql travis-ci connection
	 */
	define('DB_CONNECTION_1', serialize(array(
		'adapter' => 'PostgreSql',
		'server' => 'localhost',
		'port' => null,
		'database' => 'qcubed',
		'username' => 'postgres',
		'password' => '',
		'caching' => false,
		'profiling' => false)));
