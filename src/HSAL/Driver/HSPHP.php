<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL\Driver;

use \HSAL\HSAL;
use \HSAL\Driver;
use \HSAL\DriverInterface;


class HSPHP extends Driver implements DriverInterface
{
	private $hsr;	
	private $hsr_connected = FALSE;

	private $hsw;
	private $hsw_connected = FALSE;

	protected function getReadSocket()
	{
		if (!$this->hsr_connected)
		{
			$this->hsr = new \HSPHP\ReadSocket();

			$this->hsr->connect($this->host, $this->port_read);

			$this->hsr_connected = TRUE;
		}

		return $this->hsr;
	}

	protected function getWriteSocket()
	{
		if (!$this->hsw_connected)
		{
			$this->hsw = new \HSPHP\WriteSocket();

			$this->hsw->connect($this->host, $this->port_write);

			$this->hsw_connected = TRUE;
		}

		return $this->hsw;
	}

	public function fetchArray($table, Array $fields, $index, Array $condition, $operator)
	{
		$hs = $this->getReadSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$idx = $hs->getIndexId($database, $table, $index, implode(',',$fields));
		$hs->select($idx, $operator, $condition);
		$result = $hs->readResponse();

		if (!empty($result))
			return $result[0];
		else
			return FALSE;
	}

	public function fetchAll($table, Array $fields, $index, Array $condition, $operator, $limit, $offset)
	{
		$hs = $this->getReadSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$idx = $hs->getIndexId($database, $table, $index, implode(',', $fields));
		$hs->select($idx, $operator, $condition, $limit, $offset);

		$result = $hs->readResponse();

		if (!empty($result))
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

		$idx = $hs->getIndexId($database, $table, $index, '');
		$hs->delete($idx, $operator, $condition);
		$result = $hs->readResponse();

		if (!empty($result))
			return (bool)$result[0][0];
		else
			return FALSE;
	}

	public function insert($table, Array $values)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$fields = array_keys($values);

		$idx = $hs->getIndexId($database, $table, '', implode(',',$fields));
		$hs->insert($idx, array_values($values));

		$result = $hs->readResponse();

		return is_array($result) ? TRUE : FALSE;
	}

	public function update($table, Array $values, $index, Array $condition, $operator)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$fields = array_keys($values);

		$idx = $hs->getIndexId($database, $table, $index, implode(',', $fields));
		$hs->update($idx, $operator, $condition, array_values($values));

		$result = $hs->readResponse();

		if (!empty($result))
			return (bool)$result[0][0];
		else
			return FALSE;
	}

	public function increment($table, $field, $index, Array $condition, $operator, $increment)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$idx = $hs->getIndexId($database, $table, $index, [$field]);
		$hs->increment($idx, $operator, $condition, [$increment]);
		$result = $hs->readResponse();

		if (!empty($result))
			return (bool)$result[0][0];
		else
			return FALSE;
	}

	public function decrement($table, $field, $index, Array $condition, $operator, $decrement)
	{
		$hs = $this->getWriteSocket();

		list($database, $table) = $this->getTableDatabase($table);

		$idx = $hs->getIndexId($database, $table, $index, [$field]);
		$hs->decrement($idx, $operator, $condition, [$decrement]);
		$result = $hs->readResponse();

		if (!empty($result))
			return (bool)$result[0][0];
		else
			return FALSE;
	}
}