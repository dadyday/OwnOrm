<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

// detailed config usage w/o chain and magic

$oModel = new Model([
	'user' => [
		'name' => ['string30', 'guest', 'null' => false, 'unique' => true],
		'password' => ['string', null],
	],
	'login' => [
		'user_id' => 'related:user, index:userlogin',
		'logged' => 'date, now, index:userlogin',
	]
]);

include 'check.php';
