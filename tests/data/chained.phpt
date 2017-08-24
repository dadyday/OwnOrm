<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

$oModel = include __DIR__.'/prepare.php';

$oModel
	->new('author')
		->set('name', 'God Himself')
	->new('author', ['name' => 'Heinrich Kramer'])
	->new('author', ['Jakob Sprenger'])
	->new('publisher', 'Roman Inquisition')
;
Is::same('God Himself', $oModel->get('author', 0)->name);
Is::same('Heinrich Kramer', $oModel->get('author', 1)->name);
Is::same('Jakob Sprenger', $oModel->get('author', 2)->name);
Is::same('Roman Inquisition', $oModel->get('publisher', 0)->name);

$oModel
	->newBook()
		->setTitle('Hexenhammer')
		->setIsbn('1234-1234-1234')
		->setDescription('Malleus maleficarum')
		->setPublisher($oModel->getPublisher(0))
	->newBook('Bible', '0000-0000-0000', null, 'The book of the books')
		->refPublisherByName('Roman Inquisition')
	->newBook([
		'Bible for Dummies',
		'0000-0000-1111',
		'description' => 'The book of the books',
		'publisher' => $oModel->newPublisher('For Dummies')->row
	])
;

#bdump($oModel->rows);

Is::type('OwnOrm\Row', $oBook = $oModel->getBookByTitle('Hexenhammer'));
Is::same($oBook, $oModel->rows['book'][0]);
Is::same('Hexenhammer', $oBook->title);
Is::same(100, $oBook->price);
Is::same($oModel->rows['publisher'][0], $oBook->publisher);

Is::type('OwnOrm\Row', $oBook2 = $oModel->getBookByIsbn('0000-0000-0000'));
Is::same($oBook2, $oModel->rows['book'][1]);
Is::same('Bible', $oBook2->title);
Is::same(100, $oBook2->price);
Is::same($oBook2->publisher, $oBook->publisher);

Is::type('OwnOrm\Row', $oBook3 = $oModel->getBook(2));
Is::same($oBook3, $oModel->rows['book'][2]);
Is::same('Bible for Dummies', $oBook3->title);
Is::same(100, $oBook3->price);
Is::same($oBook3->publisher, $oModel->rows['publisher'][1]);
//*/
