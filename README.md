[![Build Status](https://travis-ci.org/Junker/HSAL.svg?branch=master)](https://travis-ci.org/Junker/HSAL)
[![Latest Stable Version](https://poser.pugx.org/junker/hsal/v/stable)](https://packagist.org/packages/junker/hsal)
[![Total Downloads](https://poser.pugx.org/junker/hsal/downloads)](https://packagist.org/packages/junker/hsal)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Junker/HSAL/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Junker/HSAL/?branch=master)
[![License](https://poser.pugx.org/junker/hsal/license)](https://packagist.org/packages/junker/hsal)


# HSAL - HandlerSocket Abstraction Layer library for PHP


## Requirements
HSAL requires PHP 5.4 or later.

one of handlerSocket Libraries: 
* HSPHP (https://github.com/tz-lom/HSPHP)
* Handlersocketi (https://github.com/kjdev/php-ext-handlersocketi) [Installation instruction](docs/handlersocketi.md) 


## Installation
The best way to install HSAL is to use a [Composer](https://getcomposer.org/download):

    php composer.phar require junker/hsal




## Examples

```php
use HSAL\HSAL;

$hs = new HSAL('localhost', 'database');

//SELECT id,title FROM pages WHERE id=5
$page = $hs->fetchAssoc('pages', 
	['id', 'title'], 
	[HSAL::INDEX_PRIMARY => 5]
); 
print_r($page); // ['id' => 5, 'title' => 'page number 5']
```
```php
//SELECT title FROM pages WHERE id=5
$title = $hs->fetchColumn('pages', 'title', [HSAL::INDEX_PRIMARY => 5]); 
print_r($title); // page number 5
```
```php
//SELECT id,page_id,title FROM pages_lang WHERE page_id=5 AND language_id=2
$page = $hs->fetchArray('pages_lang', 
	['id', 'page_id', 'title'], 
	['page_lang' => [5,2]]
); 
print_r($title); // [21, 5, 'numéro de la page 5']
```
```php
//SELECT id,title FROM pages WHERE view_count>10 LIMIT 5
$pages = $hs->fetchAll('pages', 
	['id', 'title'], 
	['view_count' => 10], 
	HSAL::OPERATOR_GREATER, 
	5
); 
print_r($pages); 
// [['id' => 4, 'title' => 'page number 4'], ['id' => 5, 'title' => 'page number 5']] 
```
```php
//can make request to another database(i.e. dev_database) without creating new HSAL instance
//SELECT title FROM dev_database.pages WHERE id=5
$title = $hs->fetchColumn('dev_database.pages', 'title', [HSAL::INDEX_PRIMARY => 5]); 
```

```php
use HSAL\HSAL;

$hs = new HSAL('localhost', 'database', ['driver' => HSAL::DRIVER_HSPHP, 'port_write' => 5555]);

$hs->insert('pages', ['id' => 6, 'title' => 'New page']);

$result = $hs->delete('pages', ['id' => 6]);
print_r($result); // 1
$result = $hs->delete('pages', ['id' => 6]);
print_r($result); // 0, because record doesn't exists

$hs->update('pages', ['title' => 'Best page'], [HSAL::INDEX_PRIMARY => 5]);

$hs->increment('pages', 'view_count', [HSAL::INDEX_PRIMARY => 5]);

```


## API

### Methods

**__construct($host, $database, $options)**

name | description | type
--- | --- | ---
host | ip or hostname of HandlerSocket server | string, required
database | database name | string, required
options | array of options | assoc array, optional
Options:
+ driver: default HSAL::DRIVER_AUTO
+ port_read: read port (int)
+ port_write: write port (int)
+ timeout: timeout, works only for Handlersocketi driver (double, default: 5)
+ rw_timeout: read/write timeout, works only for Handlersocketi driver (double, default: 5)

___


**fetchArray($table, $fields, $index_condition, $operator)**

name | description | type
--- | --- | ---
table | table name | string, required
fields | requested fields | array, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
___


**fetchAssoc($table, $fields, $index_condition, $operator)**

name | description | type
--- | --- | ---
table | table name | (string, required)
fields | requested fields | array, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
___

**fetchColumn($table, $field, $index_condition, $operator)**

name | description | type
--- | --- | ---
table | table name | string, required
field | requested field | string, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
___

**fetchAll($table, $fields, $index_condition, $operator, $limit, $offset)**

name | description | type
--- | --- | ---
table | table name | string, required
fields | requested field | string, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
limit | limit rows | int, optional, default: 1000
offset | offset | int, optional, default: 0
___

**delete($table, $index_condition, $operator)**

name | description | type
--- | --- | ---
table | table name | string, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
___

**insert($table, $values)**

name | description | type
--- | --- | ---
table | table name | string, required
values | array of fields with values | assoc array, required
___

**update($table, $values, $index_condition, $operator)**

name | description | type
--- | --- | ---
table | table name | string, required
values | associative array of fields with values | assoc array, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
___

**increment($table, $field, $index_condition, $operator, $increment)**

name | description | type
--- | --- | ---
table | table name | string, required
field | requested field | string, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
increment | increment value | int, optional, default: 1
___

**decrement($table, $field, $index_condition, $operator, $decrement)**

name | description | type
--- | --- | ---
table | table name | string, required
field | requested field | string, required
index_condition | query condition | assoc array, required
operator | operator for query condition | optional, default: HSAL::OPERATOR_EQUAL
decrement | decrement value | int, optional, default: 1
___

```php
//Operators
HSAL::OPERATOR_EQUAL = '=';
HSAL::OPERATOR_LESS = '<';
HSAL::OPERATOR_LESS_EQUAL = '<=';
HSAL::OPERATOR_GREATER = '>';
HSAL::OPERATOR_GREATER_EQUAL = '>=';

//Drivers
HSAL::DRIVER_AUTO //auto-detect
HSAL::DRIVER_HSPHP
HSAL::DRIVER_HANDLERSOCKETI
```




## Roadmap
* php-handlersocket Driver (https://code.google.com/p/php-handlersocket/)
* batch queries 
* Authorization
