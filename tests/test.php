<?php
require_once 'cfg.php';

use \RedBeanPHP\R;

R::nuke();

$oMojito = R::dispense('cocktail'); $i = -1;
$oMojito->name = 'Mojito';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::findOrCreate('ingredient', [
											'name' => 'Limette',
											'volumen' => 0.4,
											'category' => R::findOrCreate('category', ['name' => 'Obst/Gemüse'])]);
$oMojito->ownReceipeList[$i]->quantity = 0.5;
$oMojito->ownReceipeList[$i]->unit = R::dispense('unit');
$oMojito->ownReceipeList[$i]->unit->short = 'Stk';
$oMojito->ownReceipeList[$i]->unit->volumen = 1;
$oMojito->ownReceipeList[$i]->description = 'vierteln und in Glass geben';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::dispense('ingredient');
$oMojito->ownReceipeList[$i]->ingedient->name = 'Minzblatt';
$oMojito->ownReceipeList[$i]->ingedient->volumen = 0;
$oMojito->ownReceipeList[$i]->ingedient->category = R::findOrCreate('category', ['name' => 'Gewürze']);
$oMojito->ownReceipeList[$i]->circa = true;
$oMojito->ownReceipeList[$i]->quantity = 4;
$oMojito->ownReceipeList[$i]->unit = R::findOrCreate('unit', 'short = "Stk"');
$oMojito->ownReceipeList[$i]->description = 'dazu geben';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::dispense('ingredient');
$oMojito->ownReceipeList[$i]->ingedient->name = 'Brauner Zucker';
$oMojito->ownReceipeList[$i]->ingedient->volumen = 1.0;
$oMojito->ownReceipeList[$i]->ingedient->category = R::findOrCreate('category', ['name' => 'Gewürze']);
$oMojito->ownReceipeList[$i]->quantity = 4;
$oMojito->ownReceipeList[$i]->unit = R::dispense('unit');
$oMojito->ownReceipeList[$i]->unit->short = 'BL';
$oMojito->ownReceipeList[$i]->unit->name = 'Barlöffel';
$oMojito->ownReceipeList[$i]->unit->volumen = 0.05;
$oMojito->ownReceipeList[$i]->description = 'dazu geben und stösseln';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::dispense('ingredient');
$oMojito->ownReceipeList[$i]->ingedient->name = 'Crusheis';
$oMojito->ownReceipeList[$i]->ingedient->volumen = 1.0;
$oMojito->ownReceipeList[$i]->ingedient->category = R::findOrCreate('category', ['name' => 'Alkoholfreies']);
$oMojito->ownReceipeList[$i]->quantity = 2;
$oMojito->ownReceipeList[$i]->unit = R::dispense('unit');
$oMojito->ownReceipeList[$i]->unit->short = 'EL';
$oMojito->ownReceipeList[$i]->unit->name = 'Esslöffel';
$oMojito->ownReceipeList[$i]->unit->volumen = 0.1;
$oMojito->ownReceipeList[$i]->description = 'auffüllen';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::dispense('ingredient');
$oMojito->ownReceipeList[$i]->ingedient->name = 'Rum';
$oMojito->ownReceipeList[$i]->ingedient->volumen = 1.0;
$oMojito->ownReceipeList[$i]->ingedient->category = R::findOrCreate('category', ['name' => 'Spirituose']);
$oMojito->ownReceipeList[$i]->quantity = 4;
$oMojito->ownReceipeList[$i]->unit = R::dispense('unit');
$oMojito->ownReceipeList[$i]->unit->short = 'cl';
$oMojito->ownReceipeList[$i]->unit->volumen = 0.1;
$oMojito->ownReceipeList[$i]->description = 'dazu geben und verrühren';

$oMojito->ownReceipeList[] = R::dispense('receipe'); $i++;
$oMojito->ownReceipeList[$i]->ingedient = R::findOrCreate('ingredient', ['name' => 'Crusheis']);
$oMojito->ownReceipeList[$i]->circa = true;
$oMojito->ownReceipeList[$i]->quantity = 2;
$oMojito->ownReceipeList[$i]->unit = R::findOrCreate('unit', 'short = "EL"');
$oMojito->ownReceipeList[$i]->description = 'auffüllen bis zum Rand';


dump($oMojito);

R::store($oMojito);
