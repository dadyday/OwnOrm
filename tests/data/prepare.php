<?php
require_once 'cfg.php';

use OwnOrm\Model;
return $oModel = Model::create()
	->entity('book')
		->property('title')
			->type('string', 40)
			->default(null)
		->property('isbn')
			->unique()
		->property('price', ['type' => 'int', 'default' => 100])
		->property('description')
		->property('publisher', ['related' => 'publisher', 'index' => true])

	->entity('publisher', ['name' => 'string40'])
	->entity('author', ['name' => 'string40'])
	->entity('writtenBy')
		->property('book')
			->related('book')
			->unique('bookAuthor')
		->property('author', ['related' => 'author', 'unique' => 'bookAuthor'])
;
