<?php
require_once 'cfg.php';

use OwnOrm\Model;
use OwnOrm\Data;
use Tester\Assert as Is;

$oModel = include __DIR__.'/prepare.php';
$oData = new Data($oModel);

$oPub = $oData->createRow('publisher', ['name' => 'Roman Inquisition']);
Is::same($oPub, $oData->getRow('publisher', 0));
Is::same('Roman Inquisition', $oPub->name);

$oBook = $oData->createRow('book');
$oBook->title = 'Hexenhammer';
$oBook->isbn = '1234-1234-1234';
$oBook->description = 'Malleus maleficarum';
$oBook->publisher = $oPub;
Is::same($oBook, $oData->getRow('book', 0));
Is::same('Hexenhammer', $oBook->title);
Is::same(100, $oBook->price);
Is::same($oPub, $oBook->publisher);

$oBook = $oData->createRow('book');
$oBook->title = 'Bible';
$oBook->isbn = '0000-0000-0000';
$oBook->description = 'The book of the books';
Is::same($oBook, $oData->getRow('book', 1));
Is::same('Bible', $oBook->title);
Is::same(100, $oBook->price);

$oBook = $oData->createRow('book', [
	'title' => 'Bible for dummies',
	'isbn' => '0000-0000-1111',
	'description' => 'The book of the book of the books',
	'publisher' => $oData->createRow('publisher', 'For Dummies'),
]);
Is::same($oBook, $oData->getRow('book', 2));
Is::same('Bible for dummies', $oBook->title);
Is::same(100, $oBook->price);
Is::same($oBook->publisher, $oData->getRow('publisher', 1));


#dump($oData->rows);
