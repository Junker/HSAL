<?php

use HSAL\HSAL;

class HandlersocketiTest extends PHPUnit_Framework_TestCase
{
	public function testFetchArray()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->fetchArray('test2', ['id','name','cnt'], [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue(is_array($result));
		$this->assertEquals(3, count($result));
		$this->assertEquals(3, $result[0]);
		$this->assertEquals('page 3', $result[1]);
		$this->assertEquals(0, $result[2]);
	}

	public function testFetchAssoc()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->fetchAssoc('test2', ['id','name','cnt'], [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue(is_array($result));
		$this->assertEquals(3, count($result));
		$this->assertEquals(3, $result['id']);
		$this->assertEquals('page 3', $result['name']);
		$this->assertEquals(0, $result['cnt']);
	}

	public function testFetchColumn()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->fetchColumn('test2', 'name', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertEquals('page 3', $result);
	}

	public function testFetchAll()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->fetchAll('test2', ['id', 'name'], [HSAL::INDEX_PRIMARY => 3], HSAL::OPERATOR_LESS_EQUAL);

		$this->assertTrue(is_array($result));
		$this->assertEquals(3, count($result));

		$this->assertEquals(3, $result[0]['id']);
		$this->assertEquals(1, $result[2]['id']);
		
		$this->assertEquals('page 3', $result[0]['name']);
		$this->assertEquals('page 1', $result[2]['name']);
	}

	public function testDelete()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->delete('test2', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue($result);

		$result = $hs->delete('test2', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertFalse($result);
	}

	public function testInsert()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->insert('test2', ['id' => 3, 'name' => 'new page', 'cnt' => 0]);

		$result = $hs->fetchColumn('test2', 'name', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertEquals('new page', $result);
	}

	public function testUpdate()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->update('test2', ['name' => 'updated page'], [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue($result);

		$result = $hs->fetchColumn('test2', 'name', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertEquals('updated page', $result);
	}

	public function testIncrement()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->increment('test2', 'cnt', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue($result);

		$result = $hs->fetchColumn('test2', 'cnt', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertEquals(1, $result);
	}

	public function testDecrement()
	{
		$hs = new HSAL('localhost', 'hstest', HSAL::DRIVER_HANDLERSOCKETI);

		$result = $hs->decrement('test2', 'cnt', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertTrue($result);

		$result = $hs->fetchColumn('test2', 'cnt', [HSAL::INDEX_PRIMARY => 3]);

		$this->assertEquals(0, $result);
	}

}
