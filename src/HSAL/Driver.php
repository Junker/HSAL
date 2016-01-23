<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL;

class Driver
{
	public $database;
	public $host;


	public function __construct($host, $database)
	{
		$this->database = $database;
		$this->host = $host;
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