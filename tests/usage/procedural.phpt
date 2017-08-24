<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

// single calls usage w/o chain and magic

$oModel = new Model();
$oUser = $oModel->createEntity('user');
$oProp = $oUser->createProperty('name');
Is::error(function() use ($oUser) { $oUser->createProperty('name'); }, '\Exception');

$oProp->type = 'string';
$oProp->length = 30;
$oProp->default = 'guest';
$oProp->null = false;

$oProp = $oUser->createProperty('password');
$oProp->type = 'string';
$oProp->default = null;

$oIndex = $oUser->createIndex('name');
$oIndex->type = 'unique';
$oIndex->addField('name');

$oLogin = $oModel->createEntity('login');
$oLoginUser = $oLogin->createProperty('user_id');
$oLoginUser->type = 'integer';
$oLoginUser->related = 'user';

$oLoginLogged = $oLogin->createProperty('logged');
$oLoginLogged->type = 'date';
$oLoginLogged->default = 'now';

$oIndex = $oLogin->createIndex('userlogin');
$oIndex->addField($oLoginUser);
$oIndex->addField('logged');

include 'check.php';
