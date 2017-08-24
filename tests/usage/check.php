<?php

use OwnOrm\Model;
use Tester\Assert as Is;

Is::type('OwnOrm\Model', $oModel);
Is::same(2, count($oModel->entities));

$oUser = $oEntity = $oModel->findEntity('user');
Is::type('OwnOrm\Entity', $oEntity);
Is::same(2, count($oEntity->properties));

$oUserName = $oProperty = $oEntity->findProperty('name');
Is::type('OwnOrm\Property', $oProperty);
Is::same('name', $oProperty->name);
Is::same('string', $oProperty->type);
Is::same(30, $oProperty->length);
Is::same('guest', $oProperty->default);
Is::false($oProperty->null);

$oUserPass = $oProperty = $oEntity->findProperty('password');
Is::type('OwnOrm\Property', $oProperty);
Is::same('password', $oProperty->name);
Is::same('string', $oProperty->type);
Is::same(40, $oProperty->length);
Is::null($oProperty->default);
Is::true($oProperty->null);

Is::same(1, count($oEntity->indices));
$oIndex = $oEntity->findIndex('name');
Is::type('OwnOrm\Index', $oIndex);
Is::same(1, count($oIndex->fields));
Is::same($oUserName, $oIndex->fields['name']);

$oLogin = $oEntity = $oModel->findEntity('login');
Is::type('OwnOrm\Entity', $oEntity);
Is::same(2, count($oEntity->properties));

$oLoginUser = $oProperty = $oEntity->findProperty('user_id');
Is::same($oUser, $oLoginUser->related);

$oLoginLogged = $oProperty = $oEntity->findProperty('logged');



Is::same(1, count($oEntity->indices));
$oIndex = $oEntity->findIndex('userlogin');
Is::type('OwnOrm\Index', $oIndex);
Is::same(2, count($oIndex->fields));
Is::same($oLoginUser, $oIndex->fields['user_id']);
Is::same($oLoginLogged, $oIndex->fields['logged']);
