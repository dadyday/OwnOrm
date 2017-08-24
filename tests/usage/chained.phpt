<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

// single calls usage w/o chain and magic

$oModel = Model::create()
	->entity('user')
		->property('name')
			->type('string')
			->length(30)
			->default('guest')
			->null(false)
			->unique()
		->property('password', 'string', null)
	->entity('login')
		->property('user_id', 'related:user')
		->property('logged', 'date', 'now')
		->index('userlogin', ['user_id', 'logged'])
;
#dump($oModel->entities);
include 'check.php';
