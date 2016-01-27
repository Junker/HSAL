<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL;

abstract class Driver
{
	public $database;
	public $host;
	public $port_read = 9998;
	public $port_write = 9999;

	protected $options = [];

	public function __construct($host, $database, Array $options)
	{
		$this->database = $database;
		$this->host = $host;


		if (!empty($options))
		{
			$this->options = $options;

			$this->port_read = isset($options['port_read']) ? $this->options['port_read'] : $this->port_read;
			$this->port_write = isset($options['port_write']) ? $this->options['port_write'] : $this->port_write;
		}
	}



	public function fetchColumn($table, $field, $index, Array $condition, $operator)
	{
		$result = $this->fetchArray($table, [$field], $index, $condition, $operator);

		return empty($result) ? FALSE : $result[0];
	}

	public function fetchAssoc($table, Array $fields, $index, Array $condition, $operator)
	{
		$result = $this->fetchArray($table, $fields, $index, $condition, $operator);

		if (!empty($result))
			return array_combine($fields, $result);
		else
			return FALSE;
	}

	public function getTableDatabase($table)
	{
		$database = $this->database;

		if (strpos($table, '.'))
		{
			list($database, $table) = explode('.', $table);
		}

		return [$database, $table];
	}
}