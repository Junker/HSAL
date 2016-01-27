<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL;

class HSAL
{
	public $driver;

	private $hs;

	const DRIVER_AUTO = 1;
	const DRIVER_HANDLERSOCKETI = 2;
	const DRIVER_HSPHP = 3;

	const OPERATOR_EQUAL = '=';
	const OPERATOR_LESS = '<';
	const OPERATOR_LESS_EQUAL = '<=';
	const OPERATOR_GREATER = '>';
	const OPERATOR_GREATER_EQUAL = '>=';

	const INDEX_PRIMARY = '';


	public function __construct($host, $database, Array $options = [])
	{
		if (!extension_loaded('handlersocketi') && (!class_exists('\\HSPHP\\ReadSocket')))
		{
			throw new \Exception('Error: cannot detect HandlerSocket library. please, install handlersocketi module or HSPHP library');
		}

		$driver = self::DRIVER_AUTO;

		if (isset($options['driver']))
			$driver = $options['driver'];

		if ($driver == self::DRIVER_AUTO)
		{
			if (extension_loaded('handlersocketi'))
			{
				$driver = self::DRIVER_HANDLERSOCKETI;
			}
			else if (class_exists('\\HSPHP\\ReadSocket'))
			{
				$driver = self::DRIVER_HSPHP;
			}
		}

		if ($driver == self::DRIVER_HANDLERSOCKETI)
		{
			$this->hs = new \HSAL\Driver\Handlersocketi($host, $database, $options);
		}
		else if ($driver == self::DRIVER_HSPHP)
		{
			$this->hs = new \HSAL\Driver\HSPHP($host, $database, $options);
		}


		$this->driver = $driver;
	}


	public function fetchArray($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->fetchArray($table, $fields, $index, $condition, $operator);
	}

	public function fetchAssoc($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->fetchAssoc($table, $fields, $index, $condition, $operator);
	}

	public function fetchColumn($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->fetchColumn($table, $field, $index, $condition, $operator);
	}

	public function fetchAll($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $limit = 1000, $offset = 0)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->fetchAll($table, $fields, $index, $condition, $operator, $limit, $offset);
	}

	public function delete($table, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->delete($table, $index, $condition, $operator);
	}

	public function insert($table, Array $values)
	{
		return $this->hs->insert($table, $values);
	}

	public function update($table, Array $values, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->update($table, $values, $index, $condition, $operator);
	}

	public function increment($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $increment = 1)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->increment($table, $field, $index, $condition, $operator, $increment);
	}

	public function decrement($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $decrement = 1)
	{
		list($index, $condition) = self::parse_index_condition($index_condition);

		return $this->hs->decrement($table, $field, $index, $condition, $operator, $decrement);
	}


	private static function parse_index_condition($index_condition)
	{
		$index = array_keys($index_condition)[0];
		$condition = is_array($index_condition[$index]) ? $index_condition[$index] : [$index_condition[$index]];

		return [$index, $condition];
	} 

}