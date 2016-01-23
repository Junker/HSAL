<?php

// Copyright (c) Dmitry Kosenkov,  All rights reserved.

namespace HSAL\Driver;

interface DriverInterface
{
	public function __construct($host, $database);

	public function fetchArray($table, Array $fields, $index, Array $condition, $operator);

	public function fetchAll($table, Array $fields, $index, Array $condition, $operator, $limit, $offset);

	public function delete($table, $index, Array $condition, $operator);

	public function insert($table, Array $values);

	public function update($table, Array $values, $index, Array $condition, $operator);

	public function increment($table, $field, $index, Array $condition, $operator, $increment);

	public function decrement($table, $field, $index, Array $condition, $operator, $decrement);
}