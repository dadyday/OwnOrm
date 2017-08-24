<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

// detailed config usage w/o chain and magic

$oModel = new Model([
	'user' => [
		'properties' => [
			'name' => ['type' => 'string', 'length' => 30, 'default' => 'guest', 'null' => false],
			'password' => ['type' => 'string', 'length' => 40, 'default' => null, 'null' => true],
		],
		'indices' => [
			'name' => ['type' => 'unique', 'fields' => ['name']],
		],
	],
	'login' => [
		'properties' => [
			'user_id' => ['type' => 'integer', 'related' => 'user'],
			'logged' => ['type' => 'date', 'default' => 'now'],
		],
		'indices' => [
			'userlogin' => ['type' => 'index', 'fields' => ['user_id', 'logged']],
		],
	]
]);

include 'check.php';
