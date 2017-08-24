<?php
namespace OwnOrm;

use Nette;

/**
 * @property string $name
 * @property string $type
 * @property mixed $related
 * @property integer $length
 * @property mixed $default
 * @property boolean $null
 * @property-write mixed $unique
 * @property-write mixed $index
 */
class Property {
	use Nette\SmartObject;

	public
		$oEntity,
		$name,
		$null = true;

	protected
		$type,
		$length = 0,
		$default = null,
		$related = null;

	function __construct($oEntity, $name, $aCfg = []) {
		if ($oEntity->findProperty($name)) throw new \Exception("property $oEntity->name::$name already exists");
		$this->oEntity = $oEntity;
		$this->name = $name;
		$this->config($aCfg);
	}

	function __get($name) {
		$func = 'get'.ucfirst($name);
		if (method_exists($this, $func)) return $this->$func();
		return $this->$name;
	}

	function __set($name, $value) {
		$func = 'set'.ucfirst($name);
		if (method_exists($this, $func)) $this->$func($value);
		else $this->$name = $value;
	}

	function config($aCfg) {
		if (is_string($aCfg)) $aCfg = preg_split('~[,;]\s*~', $aCfg);
		foreach ($aCfg as $cfg => $value) {
			if (is_integer($cfg) && preg_match('~^(\w+):\s*(.*)$~', $value, $aMatch)) {
				list(, $cfg, $value) = $aMatch;
			};
			switch($cfg) {
				case '0': $cfg = 'type'; break;
				case '1': $cfg = 'default'; break;
			};
			$this->__set($cfg, $value);
		}
	}

	protected function _initTypeSettings($type) {
		$t = $l = $d = $n = null;
		if (preg_match('~^(\D+)(\d+)$~', $type, $aMatch)) {
			list(, $t, $l) = $aMatch;
			$this->type = $t;
			$this->length = (integer) $l;
		}
		else switch ($type) {
			case 'bool':
			case 'boolean': $t = 'boolean'; $l = 0; $d = null; $n = true; break;
			case 'int':
			case 'integer': $t = 'integer'; $l = 0; $d = 0; $n = false; break;
			case 'num':
			case 'float':
			case 'double': $t = 'float'; $l = 0; $d = 0; $n = false; break;
			case 'text': $t = 'string'; $l = 0; $d = null; $n = true; break;
			case 'str':
			case 'string': $t = 'string'; $l = 40; $d = ''; $n = false; break;
			case 'date':
			case 'time':
			case 'datetime': $t = $type; $l = 0; $d = null; $n = true; break;
			case 'ref': $t = 'integer'; $l = 0; $d = null; $n = true; break;
			case 'list': $t = 'list'; $l = 0; $d = null; $n = true; break;
			case 'NULL':
			case 'object':
				break;
			default:
				throw new \Exception("invalid property type $type");
		};

		if (!is_null($t) && is_null($this->type)) $this->type = $t;
		if (!is_null($l) && empty($this->length)) $this->length = (integer) $l;
		if (!is_null($d) && is_null($this->default)) $this->default = $d;
		if (!is_null($n) && is_null($this->null)) $this->null = $n;
		return $t;
	}

	function typed($value) {
		$this->_initTypeSettings(gettype($value));
		return $value;
	}

	function setType($type) {
		$this->_initTypeSettings($type);
	}

	function setLength($length) {
		$this->length = (integer) $length;
	}

	function setDefault($value) {
		$this->_initTypeSettings(gettype($value));
		$this->default = $value;
	}

	function setRelated($value) {
		$this->_initTypeSettings('ref');
		$this->related = $this->oEntity->oModel->findOrCreateEntity($value);
	}

	function setUnique($name = null) {
		if (is_null($name) || $name === true) $name = $this->name;
		$oIndex = $this->oEntity->findOrCreateIndex($name, ['type' => 'unique', 'fields' => []]);
		$oIndex->addField($this);
	}

	function setIndex($name = null) {
		if (is_null($name) || $name === true) $name = $this->name;
		$oIndex = $this->oEntity->findOrCreateIndex($name, ['type' => 'index', 'fields' => []]);
		$oIndex->addField($this);
	}

	function getData($value) {
		return $value;
	}
}
