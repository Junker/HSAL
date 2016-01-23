<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL\Driver;

use \HSAL\HSAL;

class Handlersocketi  extends \HSAL\Driver implements DriverInterface
{
	private $hsr;	
	private $hsr_connected = FALSE;

	private $hsw;
	private $hsw_connected = FALSE;

	const PORT_READ = 9998;
	const PORT_WRITE = 9999;

	protected function getReadSocket()
	{
		if (!$this->hsr_connected)
		{
			$this->hsr = new \HandlerSocketi($this->host, self::PORT_READ);

			$this->hsr_connected = TRUE;
		}

		return $this->hsr;
	}

	protected function getWriteSocket()
	{
		if (!$this->hsw_connected)
		{
			$this->hsw = new \HandlerSocketi($this->host, self::PORT_WRITE);

			$this->hsw_connected = TRUE;
		}

		return $this->hsw;
	}


	public function fetchArray($table, Array $fields, $index, Array $condition, $operator)
	{
		$hs = $this->getReadSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$index = $hs->openIndex($database, $table, $fields, ['index' => $index]);
		
		if (!$index)
		{
			throw new \Exception($index->getError(), 1);
		}

		$result = $index->find([$operator => $condition]);

		if (!empty($result) && is_array($result))
			return $result[0];
		else
			return FALSE;
	}

	public function fetchAll($table, Array $fields, $index, Array $condition, $operator, $limit, $offset)
	{
		$hs = $this->getReadSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$index = $hs->openIndex($database, $table, $fields, ['index' => $index]);

		if (!$index)
		{
			throw new \Exception($index->getError(), 1);
		}

		$result = $index->find([$operator => $condition], ['limit' => $limit, 'offset' => $offset]);

		if (!empty($result) && is_array($result))
		{
			foreach ($result as $key => $arr) 
			{
				$result[$key] = array_combine($fields, $arr);
			}

			return $result;
		}
		else
			return FALSE;
	}

	public function delete($table, $index, Array $condition, $operator)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$index = $hs->openIndex($database, $table, [], ['index' => $index]);

		$result = $index->remove([$operator => $condition]);

		return (bool)$result;
	}

	public function insert($table, Array $values)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$index = $hs->openIndex($database, $table, array_keys($values));

		$result = $index->insert(array_values($values));

		return (bool)$result;
	}

	public function update($table, Array $values, $index, Array $condition, $operator)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$fields = array_keys($values);

		$index = $hs->openIndex($database, $table, array_keys($values));

		$result = $index->update([$operator => $condition], array_values($values));

		return (bool)$result;
	}

	public function increment($table, $field, $index, Array $condition, $operator, $increment)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$index = $hs->openIndex($database, $table, [$field]);

		$result = $index->update([$operator => $condition], ['+' => $increment]);

		return (bool)$result;
	}

	public function decrement($table, $field, $index, Array $condition, $operator, $decrement)
	{

		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		if ($index == HSAL::INDEX_PRIMARY) $index = 'PRIMARY';

		$index = $hs->openIndex($database, $table, [$field]);

		$result = $index->update([$operator => $condition], ['-' => $increment]);

		return (bool)$result;
	}

}