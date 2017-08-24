<?php
require_once __DIR__.'/../vendor/autoload.php';

Tracy\Debugger::enable();
Tracy\Debugger::$maxDepth = 8;

//\RedBeanPHP\R::setup('mysql:host=localhost;dbname=test', 'root', '');

if (PHP_SAPI == 'cgi-fcgi') Tester\Environment::setup();


$_PATH = preg_split(
	'~[\\\\/]~',
	(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : ''),
	-1,
	PREG_SPLIT_NO_EMPTY);
