## Defining a model

You have several ways to define your model scheme.
You can use a conventional style, like this:

```php
$oModel = new Model();
$oUserEntity = $oModel->createEntity('user');

$oUserNameProperty = $oUserEntity->createProperty('name');
$oUserNameProperty->type = 'string';
$oUserNameProperty->length = 30;
$oUserNameProperty->unique = true;

$oUserPassProperty = $oUserEntity->createProperty('password');
$oUserPassProperty->default = null;

// or compacter
$oLoginEntity = $oModel->createEntity('login');
$oLoginUserProperty = $oLoginEntity->createProperty(
	'user',
	['related' => 'user']
);
$oLoginLoggedProperty = $oLoginEntity->createProperty(
	'logged',
	[
		'type' => 'datetime',
		'default' => 'now'
	]
);

// or more compact
$oPostEntity = $oModel->createEntity(
	'post',
	[
		'user' => 'related:user',
		'subject' => 'string120',
		'body' => 'text',
		'created' => 'datetime,default:now'
	]
);
...
```

Since your Model was defined, you can create data rows:

```php
...
$oData = new Data($oModel);
$oAdminRow = $oData->createRow('user');
$oAdminRow->name = 'admin';
$oAdminRow->password = 'swordfish';

$oLoginRow = $oData->createRow('login');
$oLoginRow->user = $oAdminRow;

$oPostRow = $oData->createRow('post');
$oPostRow->user = $oAdminRow;
$oPostRow->subject = 'My first post';
$oPostRow->body = 'But i have nothing to say';
...
```

If you want less typing and like it more readable, chain your defines with the ModelBuilder:

```php
$oModel = ModelBuilder::create()
	->entity('user')
		->property('name')
			->type('string')
			->length(30)
		->property('password')
			->default(null)
	->entity('login')
		->property('user', ['related' => 'user'])
		->property('logged', 'datetime,now')

	->row('user')
		->set('name', 'admin')
		->set('password', 'swordfish')
	->row('login', [
		'user' => $oModel->get('user', ['name' => 'admin'])
	])

	->entity('post', [
		'user' => 'related:user',
		'subject' => 'string120',
		'body' => 'text',
		'created' => 'datetime,default:now'
	])
	->row('post', [
		'user' => $oModel->get('user', ['name' => 'admin']),
		'subject' => 'My first post',
		'body' => 'but i have nothing to say'
	])
;

```

You can even do this compacter:
```php
$oModel = ModelBuilder::create()
	->entity('user', [
		'name' => ['type' => 'string', 'length' => 30],
		'password' => 'string30'
	])
	->entity('login', [
		'user' => ['related' => 'user'],
		'logged' => 'datetime:now'
	])
	->new('user', [

	])
;
```

Or you setup your fixture data and your scheme in one task:
