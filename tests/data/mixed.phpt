<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

$oModel = Model::create();
$oModel
	->newCocktail()
		->setName('Mojito')
		->setDesc('Carribbean Summer Feeling', 'text')
		->refType(['name' => 'Longdrinks', 'desc' => 'The longer the better ...'])

		->addReceipe()
			->refIngredient()
				->identByName('Limette')
				->setVolume(0.4)
				->refCategory()
					->identByName('Obst/Gemüse')
					->back()
				->back()
			->setQuantity(0.5)
			->refUnit()
				->identByShort('Stk')
				->setVolume(1, 'float')
				->back()
			->setDescription('vierteln und in Glass geben', 'text')
			->back()

		->addReceipe()
			->refIngredient()
				->identByName('Minzblatt')
				->setVolume(0)
				->refCategory()
					->identByName('Gewürze')
					->back()
				->back()
			->setCirca(true)
			->setQuantity(0.5)
			->refUnit()
				->identByShort('Stk')
				->setName('Stück')
				->back()
			->setDescription('dazu geben')
			->back()

		->addReceipe()
			->refIngredient()
				->identByName('Rohrzucker')
				->setVolume(0)
				->refCategory('Gewürze')
				->back()
			->setCirca(true)
			->setQuantity(0.5)
			->refUnit()
				->identByShort('BL')
				->setName('Barlöffel')
				->setVolume(0.05)
				->back()
			->setDescription('dazu geben und stösseln')
			->back()
		->addReceipe()
			->refIngredient()
				->identByName('Crusheis')
				->setVolume(0)
				->refCategory(['name' => 'Alkoholfreies'])
				->back()
			->setCirca(true)
			->setQuantity(0.5)
			->refUnit(['short' => 'EL', 'name' => 'Esslöffel', 'volume' => 0.1])
			->setDescription('auffüllen')
			->back()
		->addReceipe()
			->refIngredient()
				->identByName('Rum')
				->setVolume(1.0)
				->refCategory(['name' => 'Spirituose'])
				->back()
			->setQuantity(4)
			->refUnit(['short' => 'cl', 'volume' => 0.1, 'name' => 'Zentiliter'])
			->setDescription('dazu geben und verrühren')
			->back()
		->addReceipe()
			->refIngredient('Crusheis')
			->setCirca(true)
			->setQuantity(2)
			->setUnit('EL')
			->setDescription('auffüllen bis zum Rand')
			->back()
		->addReceipe([
			'ingredient'  => 'Minzblatt',
			'quantity'    => 1,
			'unit'        => 'Stk',
			'description' => 'zum dekorieren',
		])
	->newCocktail()
		->setName('Lemonade')
		->setDesc('Fresh and cool without alcohol', 'text')
		->setType('Longdrinks')
		->addReceipe([
			'ingredient'  => 'Limette',
			'quantity'    => 1,
			'unit'        => 'Stk',
			'description' => 'achteln und stößeln',
		])
		->addReceipe()
			->refIngredient('Crusheis')
			->setCirca(true)
			->setQuantity(2)
			->setUnit('EL')
			->setDescription('dazugeben')
			->back()
		->addReceipe()
			->refIngredient(['Wasser', 1, 'Alkoholfreies'])
			->setCirca(true)
			->setQuantity(0.2)
			->refUnit(['l', 1, 'Liter'])
			->setDescription('auffüllen')
			->back()
//*/
;

dump($oModel->data);

Is::same(6, count($oModel->entities));
Is::same(2, count($oModel->rows['cocktail']));
Is::same(1, count($oModel->rows['type']));
Is::same(10, count($oModel->rows['receipe']));
Is::same(6, count($oModel->rows['ingredient']));
Is::same(4, count($oModel->rows['category']));
Is::same(5, count($oModel->rows['unit']));

$oCocktail = $oModel->get('cocktail', 0);
Is::type('OwnOrm\Row', $oCocktail);
Is::same('Mojito', $oCocktail->name);
Is::same('Carribbean Summer Feeling', $oCocktail->desc);
Is::same('Longdrinks', $oCocktail->type->name);
Is::same('The longer the better ...', $oCocktail->type->desc);

$aReceipe = $oModel->getRows('receipe', ['cocktail' => $oCocktail]);
Is::type('array', $aReceipe);
Is::same(7, count($aReceipe));

Is::same('Limette', $aReceipe[0]->ingredient->name);
Is::same('Minzblatt', $aReceipe[1]->ingredient->name);
Is::same('Rohrzucker', $aReceipe[2]->ingredient->name);
Is::same('Crusheis', $aReceipe[3]->ingredient->name);
Is::same('Rum', $aReceipe[4]->ingredient->name);
Is::same('Crusheis', $aReceipe[5]->ingredient->name);
Is::same('Minzblatt', $aReceipe[6]->ingredient->name);

$aUnit = $oModel->getRows('unit');
Is::type('array', $aUnit);
Is::same(5, count($aUnit));

Is::same('Stück', $aUnit[0]->name);
Is::same('Barlöffel', $aUnit[1]->name);
Is::same('Esslöffel', $aUnit[2]->name);
Is::same('Zentiliter', $aUnit[3]->name);
Is::same('Liter', $aUnit[4]->name);
