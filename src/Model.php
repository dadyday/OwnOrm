<?php
namespace OwnOrm;

use Nette;

/**
 * @property array $entities
 */
class Model implements \IteratorAggregate, \Countable, \ArrayAccess {
	use Nette\SmartObject;

	static function create($aCfg = []) {
		return new ModelFassade($aCfg);
	}

	var
		$aEntity = [];

	function __construct($aCfg = []) {
		$this->config($aCfg);
	}

	function getIterator() {
		return new \ArrayIterator($this->aEntity);
	}

	function count() {
		return count($this->aEntity);
	}

	function offsetExists($offset) {
		if (is_numeric($offset)) return count($this->aEntity) > $offset;
		return isset($this->aEntity[$offset]);
	}
	function offsetGet($offset) {
		if (is_numeric($offset)) $offset = array_keys($this->aEntity)[$offset];
		return $this->aEntity[$offset];
	}
	function offsetSet($offset, $value) {
		throw new \Exception("use createEntity to add");
	}
	function offsetUnset($offset) {
		if (is_numeric($offset)) $offset = array_keys($this->aEntity)[$offset];
		unset($this->aEntity[$offset]);
	}

	function config(array $aCfg) {
		if (!isset($aCfg['entities'])) $aCfg = ['entities' => $aCfg];
		foreach ($aCfg as $cfg => $value) {
			$this->$cfg = $value;
		}
	}

	function setEntities(array $aCfg) {
		foreach ($aCfg as $name => $aDef) {
			$this->findOrCreateEntity($name, $aDef);
		}
	}

	function getEntities() {
		return $this->aEntity;
	}

	function __get($name) {
		switch ($name) {
			case 'entities': return $this->getEntities();
		}
		return $this->findEntity($name);
	}

	function findEntity($name) {
		if (is_string($name)) ;
		elseif (is_a($name, __NAMESPACE__.'\Entity')) $name = $name->_name;
		else throw new \Exception('entity must be string or Entity object');
		return isset($this->aEntity[$name]) ? $this->aEntity[$name] : null;
	}

	function createEntity($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		return $this->aEntity[$name] = new Entity($this, $name, $aCfg);
	}

	function findOrCreateEntity($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		$oEntity = $this->findEntity($name) ?: $this->createEntity($name);
		if ($aCfg) $oEntity->config($aCfg);
		return $oEntity;
	}

}
