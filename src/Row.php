<?php
namespace OwnOrm;

use Nette;

/**
 * @property-read array $data
 */
class Row {
	use Nette\SmartObject;

	var
		$oData,
		$oEntity,
		$id,
		$aData = [],
		$empty = true;

	function __construct(Data $oData, Entity $oEntity, $aData = []) {
		$this->oData = $oData;
		$this->oEntity = $oEntity;
		$this->id = null;
		if (!is_array($aData)) $aData = array_slice(func_get_args(), 2);
		$this->setData($aData);
	}

	function setData($aData) {
		foreach ($aData as $name => $value) {
			if (is_numeric($name)) continue;
			$this->oEntity->findOrCreateProperty($name, ['value' => $value]);
		};
		$i = 0;
		foreach ($this->oEntity->properties as $name => $oProperty) {
			$value = isset($aData[$name]) ? $aData[$name] : (isset($aData[$i]) ? $aData[$i] : null);
			$this->setValue($oProperty, $value);
			$i++;
		}
	}

	function getData() {
		$aRet = [];
		foreach ($this->oEntity->properties as $name => $oProperty) {
			$aRet[$name] = $this->getValue($name);
			if (is_a($aRet[$name], __NAMESPACE__.'\Row')) $aRet[$name] = $aRet[$name]->getIdentity();
		}
		return $aRet;
	}

	function __set($name, $value) {
		if ($name == 'data') $this->setData($value);
		else $this->setValue($name, $value);
	}

	function __get($name) {
		if ($name == 'data') return $this->getData();
		return $this->getValue($name);
	}

	function __isset($name) {
		if ($name == 'data') return true;
		return !!$this->oEntity->findProperty($name);
	}

	function setValue($name, $value = null) {
		$oProperty = $this->oEntity->findOrCreateProperty($name, ['value' => $value]);
		if (!$oProperty) throw new \Exception("entity {$this->oEntity->_name} hat no property $name");
		if (!is_null($value)) {
			#bdump($value, $this->oEntity->_name.':'.$oProperty->name);
			$this->empty = false;
		}
		if ($oProperty->related && !is_null($value) && !is_a($value, __NAMESPACE__.'\Row')) {
			#bdump($value, $this->oEntity->_name.'::'.$oProperty->name);
			$value = $this->oData->findOrCreateRow($oProperty->related, $value);
			#throw new \Exception("property {$this->oEntity->_name}::$oProperty->name must be a row");
		}
		$this->aData[$oProperty->name] = is_null($value) ? $oProperty->default : $oProperty->typed($value);
	}

	function getValue($name) {
		$oProperty = $this->oEntity->findProperty($name);
		if (!$oProperty) throw new \Exception("entity {$this->oEntity->_name} hat no property $name");
		return isset($this->aData[$oProperty->name]) ? $oProperty->typed($this->aData[$oProperty->name]) : $oProperty->default;
	}

	function getIdentity() {
		$aProp = $this->oEntity->getIdentityFields();
		$aRet = [];
		foreach ($aProp as $name) {
			$aRet[$name] = $this->aData[$name];
			if (is_a($aRet[$name], __NAMESPACE__.'\Row')) $aRet[$name] = $aRet[$name]->getIdentity();
		}
		return $this->oEntity->_name.': '.join(',', $aRet);
	}
}
