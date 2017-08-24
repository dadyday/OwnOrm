<?php
namespace OwnOrm;

use Nette;

/**
 * @property array $fields
 */
class Index {
	use Nette\SmartObject;

	var
		$oEntity,
		$name,
		$type,
		$aField = [];

	function __construct($oEntity, $name, $aCfg = []) {
		if ($oEntity->findIndex($name)) throw new \Exception("index $oEntity->name::$name already exists");
		$this->oEntity = $oEntity;
		$this->name = $name;
		$this->config($aCfg);
	}

	function config(array $aCfg) {
		if (!isset($aCfg['fields'])) $aCfg = ['type' => 'index', 'fields' => $aCfg];
		foreach ($aCfg as $cfg => $value) {
			if ($cfg == 'fields') foreach ($value as $f) $this->addField($f);
			else $this->$cfg = $value;
		}
	}

	function setFields($aCfg) {
		$this->aField = [];
		foreach ($aCfg as $field) {
			$this->addField($field);
		}
	}

	function addField($field) {
		$oProperty = $this->oEntity->findProperty($field);
		$this->aField[$oProperty->name] = $oProperty;
	}

	function getFields() {
		return $this->aField;
	}

}
