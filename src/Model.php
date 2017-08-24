<?php
namespace OwnOrm;

use Nette;

/**
 * @property array $entities
 */
class Model {
	use Nette\SmartObject;

	static function create($aCfg = []) {
		return new ModelFassade($aCfg);
	}

	var
		$aEntity = [];

	function __construct($aCfg = []) {
		$this->config($aCfg);
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


	function findEntity($name) {
		if (is_string($name)) ;
		elseif (is_a($name, __NAMESPACE__.'\Entity')) $name = $name->name;
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
