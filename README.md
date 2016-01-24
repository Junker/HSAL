[![Build Status](https://travis-ci.org/Junker/HSAL.svg?branch=master)](https://travis-ci.org/Junker/HSAL)
[![Latest Stable Version](https://poser.pugx.org/junker/hsal/v/stable)](https://packagist.org/packages/junker/hsal)
[![Total Downloads](https://poser.pugx.org/junker/hsal/downloads)](https://packagist.org/packages/junker/hsal)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Junker/HSAL/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Junker/HSAL/?branch=master)
[![License](https://poser.pugx.org/junker/hsal/license)](https://packagist.org/packages/junker/hsal)


#HSAL - HandlerSocket Abstraction Layer


##Requirements
HSAL requires PHP 5.4 or later.

one of handlerSocket Libraries: 
* HSPHP (https://github.com/tz-lom/HSPHP)
* Handlersocketi (https://github.com/kjdev/php-ext-handlersocketi)


##Installation
The best way to install HSAL is to use a [Composer](https://getcomposer.org/download):

    php composer.phar require junker/hsal



##API

```php
public function __construct($host, $database, $driver = self::DRIVER_AUTO);
public function fetchArray($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL); 
public function fetchAssoc($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL);
public function fetchColumn($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL);
public function fetchAll($table, Array $fields, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $limit = 1000, $offset = 0);
public function delete($table, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL);
public function insert($table, Array $values);
public function update($table, Array $values, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL);
public function increment($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $increment = 1);
public function decrement($table, $field, Array $index_condition, $operator = HSAL::OPERATOR_EQUAL, $decrement = 1);

//Operators
HSAL::OPERATOR_EQUAL = '=';
HSAL::OPERATOR_LESS = '<';
HSAL::OPERATOR_LESS_EQUAL = '<=';
HSAL::OPERATOR_GREATER = '>';
HSAL::OPERATOR_GREATER_EQUAL = '>=';
```

##Examples

```php
use HSAL\HSAL;

$hs = new HSAL('localhost', 'database');

$page = $hs->fetchAssoc('pages', ['id', 'title'], [HSAL::INDEX_PRIMARY => 5]); //SELECT id,title FROM pages WHERE id=5
print_r($page); // ['id' => 5, 'title' => 'page number 5']

$title = $hs->fetchColumn('pages', 'title', [HSAL::INDEX_PRIMARY => 5]); //SELECT title FROM pages WHERE id=5
print_r($title); // page number 5

$page = $hs->fetchArray('pages_lang', ['id', 'page_id', 'title'], ['page_lang' => [5,2]]); //SELECT id,page_id,title FROM pages_lang WHERE page_id=5 AND language_id=2
print_r($title); // [21, 5, 'numéro de la page 5']

$pages = $hs->fetchAll('pages', ['id', 'title'], ['view_count' => 10], HSAL::OPERATOR_GREATER, 10); //SELECT id,title FROM pages WHERE view_count>10 LIMIT 10
print_r($pages); // [['id' => 4, 'title' => 'page number 4'], ['id' => 5, 'title' => 'page number 5']] 

//can make request to another database(i.e. dev_database) without creating new HSAL instance
$title = $hs->fetchColumn('dev_database.pages', 'title', [HSAL::INDEX_PRIMARY => 5]); //SELECT title FROM dev_database.pages WHERE id=5

```

```php
use HSAL\HSAL;

$hs = new HSAL('localhost', 'database', HSAL::DRIVER_HSPHP);

$hs->insert('pages', ['id' => 6, 'title' => 'New page']);

$result = $hs->delete('pages', ['id' => 6]);
print_r($result); // 1
$result = $hs->delete('pages', ['id' => 6]);
print_r($result); // 0

$hs->update('pages', ['title' => 'Best page'], [HSAL::INDEX_PRIMARY => 5]);

$hs->increment('pages', 'view_count', [HSAL::INDEX_PRIMARY => 5]);


```

##Roadmap
* php-handlersocket Driver (https://code.google.com/p/php-handlersocket/)
* batch queries 
* request timeout option
* Authorization