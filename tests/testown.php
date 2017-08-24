<?php
require_once 'cfg.php';

use OwnOrm\Model;

// basic usage w/o chain and magic

$oModel = new Model();
$oModel->createEntities([
	'user' => [
		'name' => ['type' => 'string', 'length' => 30, 'default' => null, 'null' => true],
		'password' => ['type' => 'string', 'length' => 30, 'default' => null, 'null' => true]
	],
	'login' => [
		'user_id' => ['type' => 'integer', 'related' => 'user'],
		'logged' => ['type' => 'date', 'default' => 'now']
	]
]);


dump($oModel);
return;

$oRepo = new Repo();
$oBook = $oRepo
	->entity('book')
	->property('title')
		->type('string', 40)
		->value('Hexenhammer')
		->default(null)
	->property('isbn')
	->property('price', ['type' => 'int', 'default' => 100])
	->property('description', 'Ein Standardwerk')
	->owns('publisher')
		->property('name')->type('string')
		->back()
	->belongs('writer')
		->owns('author')
			->property('name')
			->back()
		->back()
	->entity('book')
		->data(['title' => 'Bible', 'isbn' => '123-123-123-123'])
;

dump($oRepo->getSchema());
dump($oRepo->getData());
return;
$oRepo = new Repo();
$oMojito = $oRepo('cocktail')
	->setName('Mojito')
	->addReceipe('receipe')
		->ownIngredient('ingredient')
			->identByName('Limette')
			->setVolumen(0.4)
			->ownCategory('category')
				->identByName('Obst/Gemüse')
				->back()
			->back()
		->setQuantity(0.5)
		->ownUnit('unit')
			->identByShort('Stk')
			->setVolumen(1, 'float')
			->back()
		->setDescription('vierteln und in Glass geben', 'text')
		->back()

	->addReceipe('receipe')
		->ownIngredient('ingredient')
			->identByName('Minzblatt')
			->setVolumen(0)
			->ownCategory('category')
				->identByName('Gewürze')
				->back()
			->back()
		->setCirca(true)
		->setQuantity(0.5)
		->ownUnit('unit')
			->identByShort('Stk')
			->setVolumen(1, 'float')
			->back()
		->setDescription('dazu geben')
		->back()

	->addReceipe('receipe')
		->ownIngredient('ingredient')
			->identByName('Rohrzucker')
			->setVolumen(0)
			->ownCategory('category', 'Gewürze')
			->back()
		->setCirca(true)
		->setQuantity(0.5)
		->ownUnit('unit')
			->identByShort('BL')
			->setName('Barlöffel')
			->setVolumen(0.05)
			->back()
		->setDescription('dazu geben und stösseln')
		->back()

	->addReceipe('receipe')
		->ownIngredient('ingredient')
			->identByName('Crusheis')
			->setVolumen(0)
			->ownCategory('category', ['name' => 'Alkoholfreies'])
			->back()
		->setCirca(true)
		->setQuantity(0.5)
		->ownUnit('unit', ['short' => 'EL', 'name' => 'Esslöffel', 'volume' => 0.1])
		->setDescription('auffüllen')
		->back()

	->addReceipe('receipe')
		->ownIngredient('ingredient')
			->identByName('Rum')
			->setVolumen(1.0)
			->ownCategory('category', ['name' => 'Spirituose'])
			->back()
		->setQuantity(4)
		->ownUnit($oRepo('unit', ['short' => 'cl', 'volume' => 0.1]))
		->setDescription('dazu geben und verrühren')
		->back()

	->addReceipe('receipe')
		->ownIngredient('ingredient', 'Crusheis')
		->setCirca(true)
		->setQuantity(2)
		->ownUnit('unit', 'EL')
		->setDescription('auffüllen bis zum Rand')
		->back()

	->addReceipe('receipe', [
		'ingredient'  => 'Minzblatt',
		'quantity'    => 1,
		'unit'        => 'EL',
		'description' => 'zum dekorieren',
	])

	#->save()
;

dump($oMojito->getSchema());
