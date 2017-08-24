<?php
namespace OwnOrm;

use Nette;

/**
 * @property array $properties
 * @property array $indices
 */
class Entity {
	use Nette\SmartObject;

	var
		$oModel,
		$name,
		$aProperty = [],
		$aIndex = [],
		$aIdentity = [];

	function __construct($oModel, $name, $aCfg = []) {
		if ($oModel->findEntity($name)) throw new \Exception("entity $name already exists");
		$this->oModel = $oModel;
		$this->name = $name;
		$this->config($aCfg);
	}

	function config(array $aCfg) {
		if (!isset($aCfg['properties'])) $aCfg = ['properties' => $aCfg];
		foreach ($aCfg as $cfg => $value) {
			$this->$cfg = $value;
		}
	}

	function setProperties(array $aCfg) {
		foreach ($aCfg as $name => $aDef) {
			$this->findOrCreateProperty($name, $aDef);
		}
	}

	function getProperties() {
		return $this->aProperty;
	}

	function findProperty($name) {
		if (is_a($name, __NAMESPACE__.'\Property')) {
			$name = $name->name;
		}
		return isset($this->aProperty[$name]) ? $this->aProperty[$name] : null;
	}

	function createProperty($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		if (isset($this->aProperty[$name])) throw new \Exception("property $name always exists");
		return $this->aProperty[$name] = new Property($this, $name, $aCfg);
	}

	function findOrCreateProperty($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		$oProp = $this->findProperty($name) ?: $this->createProperty($name);
		if ($aCfg) $oProp->config($aCfg);
		return $oProp;
	}

	function setIndices(array $aCfg) {
		foreach ($aCfg as $name => $aDef) {
			$this->findOrCreateIndex($name, $aDef);
		}
	}

	function getIndices() {
		return $this->aIndex;
	}

	function findIndex($name) {
		return isset($this->aIndex[$name]) ? $this->aIndex[$name] : null;
	}

	function createIndex($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		return $this->aIndex[$name] = new Index($this, $name, $aCfg);
	}

	function findOrCreateIndex($name, $aCfg = []) {
		if (func_num_args() > 2) $aCfg = array_slice(func_get_args(),1);
		$oIndex = $this->findIndex($name) ?: $this->createIndex($name);
		if ($aCfg) $oIndex->config($aCfg);
		return $oIndex;
	}

	function getIdentityFields() {
		$unique = $index = null;
		$aRet = [];
		foreach ($this->aIndex as $name => $oIndex) {
			if ($oIndex->type == 'unique' && !$unique) $unique = $name;
			else if (!$index) $index = $name;
		}
		if (!$unique) $unique = $index;
		if ($unique) $aRet = array_keys($this->aIndex[$unique]->getFields());
		else {
			foreach ($this->aProperty as $name => $oProperty) {
				if ($oProperty->type == 'string') $aRet[] = $name;
				if (count($aRet >= 3)) break;
			}
		}
		#bdump([$aRet, $this->aIndex], $this->name);
		return $aRet;
	}

	function createRow(Data $oData, $aData = []) {
		return new Row($oData, $this, $aData);
	}

	function createRelation($entity, $property = null, $aCfg = []) {
		$oEntity = $this->oModel->findOrCreateEntity($entity, $aCfg);
		if (empty($property)) $property = $oEntity->name;
		#$oEntity->findOrCreateProperty($this->name, ['type' => 'list', 'related' => $this]);
		return $this->findOrCreateProperty($property, ['related' => $oEntity]);
	}
}
