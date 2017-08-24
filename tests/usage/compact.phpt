<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

// detailed config usage w/o chain and magic

$oModel = new Model([
	'user' => [
		'name' => ['type' => 'string', 'length' => 30, 'default' => 'guest', 'null' => false, 'unique' => true],
		'password' => ['type' => 'string', 'default' => null],
	],
	'login' => [
		'user_id' => ['type' => 'integer', 'related' => 'user', 'index' => 'userlogin'],
		'logged' => ['type' => 'date', 'default' => 'now', 'index' => 'userlogin'],
	]
]);

#dump($oModel);
include 'check.php';
