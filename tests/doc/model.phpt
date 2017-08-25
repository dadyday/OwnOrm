<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

include createDocSnippet('usage.md', 0);

#dump($oModel);

Is::type('OwnOrm\Model', $oModel);
Is::type('array', $oModel->entities);
Is::same(3, count($oModel->entities));
Is::type('\Traversable', $oModel);
Is::same(3, count($oModel));
$i = 0; foreach($oModel as $oEntity) $i++;
Is::same(3, $i);

Is::type('OwnOrm\Entity', $oModel->user);
Is::type('OwnOrm\Entity', $oModel['user']);
Is::type('OwnOrm\Entity', $oModel[0]);
Is::same('user', (string) $oModel->user);
Is::type('array', $oModel->user->properties);
Is::same(2, count($oModel->user->properties));

Is::type('OwnOrm\Property', $oModel->user->name);
Is::same('name', (string) $oModel->user->name);
Is::same('name', $oModel->user->name->name);
Is::same('string', $oModel->user->name->type);
Is::same(30, $oModel->user->name->length);
Is::same('', $oModel->user->name->default);
Is::same(false, $oModel->user->name->null);
Is::same(null, $oModel->user->name->related);

Is::type('OwnOrm\Property', $oModel->user->password);
Is::same('password', (string) $oModel->user->password);
Is::same('password', $oModel->user->password->name);
Is::same('string', $oModel->user->password->type);
Is::same(40, $oModel->user->password->length);
Is::same(null, $oModel->user->password->default);
Is::same(true, $oModel->user->password->null);
Is::same(null, $oModel->user->password->related);

Is::type('array', $oModel->user->indices);
Is::same(1, count($oModel->user->indices));
Is::same('name', array_keys($oModel->user->indices)[0]);
Is::type('OwnOrm\Index', $oModel->user->indices['name']);
Is::same('name', (string) $oModel->user->indices['name']);
Is::same('name', $oModel->user->indices['name']->name);
Is::same('unique', $oModel->user->indices['name']->type);
Is::type('array', $oModel->user->indices['name']->fields);
Is::same('name', array_keys($oModel->user->indices['name']->fields)[0]);
Is::type('OwnOrm\Property', $oModel->user->indices['name']->fields['name']);
Is::same($oModel->user->name, $oModel->user->indices['name']->fields['name']);


Is::type('OwnOrm\Entity', $oModel->login);
Is::type('OwnOrm\Entity', $oModel['login']);
Is::type('OwnOrm\Entity', $oModel[1]);
Is::same('login', (string) $oModel->login);
Is::type('array', $oModel->login->properties);
Is::same(2, count($oModel->login->properties));

Is::type('OwnOrm\Property', $oModel->login->user);
Is::same('user', (string) $oModel->login->user);
Is::same('user', $oModel->login->user->name);
Is::same('ref', $oModel->login->user->type);
Is::same(0, $oModel->login->user->length);
Is::same(null, $oModel->login->user->default);
Is::same(true, $oModel->login->user->null);
Is::type('OwnOrm\Entity', $oModel->login->user->related);
Is::same($oModel->user, $oModel->login->user->related);

Is::type('OwnOrm\Property', $oModel->login->logged);
Is::same('logged', (string) $oModel->login->logged);
Is::same('logged', $oModel->login->logged->name);
Is::same('datetime', $oModel->login->logged->type);
Is::same(0, $oModel->login->logged->length);
Is::same('now', $oModel->login->logged->default);
Is::same(true, $oModel->login->logged->null);
Is::same(null, $oModel->login->logged->related);



Is::type('OwnOrm\Entity', $oModel->post);
Is::type('OwnOrm\Entity', $oModel['post']);
Is::type('OwnOrm\Entity', $oModel[1]);
Is::same('post', (string) $oModel->post);
Is::type('array', $oModel->post->properties);
Is::same(4, count($oModel->post->properties));

Is::type('OwnOrm\Property', $oModel->post->user);
Is::same('user', (string) $oModel->post->user);
Is::same('user', $oModel->post->user->name);
Is::same('ref', $oModel->post->user->type);
Is::same(0, $oModel->post->user->length);
Is::same(null, $oModel->post->user->default);
Is::same(true, $oModel->post->user->null);
Is::type('OwnOrm\Entity', $oModel->post->user->related);
Is::same($oModel->user, $oModel->post->user->related);

Is::type('OwnOrm\Property', $oModel->post->subject);
Is::same('subject', (string) $oModel->post->subject);
Is::same('subject', $oModel->post->subject->name);
Is::same('string', $oModel->post->subject->type);
Is::same(120, $oModel->post->subject->length);
Is::same('', $oModel->post->subject->default);
Is::same(false, $oModel->post->subject->null);
Is::same(null, $oModel->post->subject->related);
