<?php
namespace OwnOrm;

use Nette;

class Type {
	use Nette\SmartObject;

	static $aType = [];

	static function getByType($type) {
		if (isset(static::$aType[$type])) {
			$oType = static::$aType[$type];
		}
		else {
			$oType = static::$aType[$type] = new static($type);
		}
		return $oType;
	}

	public
		$type;

	function __construct($type, $aCfg = []) {
		$this->name = $name;
		$this->config($aCfg);
	}

	function config($aCfg) {}



}
