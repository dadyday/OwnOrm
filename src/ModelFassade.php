<?php
namespace OwnOrm;

use Nette;

/**
 * @property array $entities
 * @property array $rows
 * @property-read Row $row
 * @property-read array $data
 */
class ModelFassade extends Model {
	use Nette\SmartObject;

	private
		$_entity,
		$_property,
		$_index,
		$oData = null,
		$_row,
		$aRowStack = [];

	function __construct() {
		$this->oData = new Data($this);
	}

// entity funcs
	function entity() {
		$this->_entity = call_user_func_array([$this, 'findOrCreateEntity'], func_get_args());
		return $this;
	}

	function property() {
		$this->_property = call_user_func_array([$this->_entity, 'findOrCreateProperty'], func_get_args());
		return $this;
	}

	function index($value = null) {
		$aArg = func_get_args();
		if (count($aArg) > 1) {
			$this->_index = call_user_func_array([$this->_entity, 'findOrCreateIndex'], $aArg);
		}
		else $this->_property->index = $value;
		return $this;
	}

	function unique($value = null) {
		$aArg = func_get_args();
		if (count($aArg) > 1) {
			$this->_index = call_user_func_array([$this->_entity, 'findOrCreateIndex'], $aArg);
		}
		else $this->_property->unique = $value;
		return $this;
	}

// property funcs
	function type($value = null) { $this->_property->type = $value; return $this; }
	function length($value = null) { $this->_property->length = $value; return $this; }
	function _default($value = null) { $this->_property->default = $value; return $this; }
	function null($value = null) { $this->_property->null = $value; return $this; }
	function related($value = null) { $this->_property->related = $value; return $this; }

// data funcs
	function _new($entity, $aData = null, $aCfg = null) {
		if (!is_null($aData) && !is_array($aData)) {
			$aData = array_slice(func_get_args(), 1);
			$aCfg = null;
		}
		$oRow = $this->oData->createRow($entity, $aData, $aCfg);
		$this->_pushRow($oRow);
		return $this;
	}

	function back() {
		$this->_popRow();
		return $this;
	}

	function getRow() {
		return $this->_row;
	}

	function getRows($entity = null, $filter = null) {
		if (is_null($entity)) return $this->oData->aRow;
		if (is_null($filter)) return $this->oData->getRows($entity);
		return $this->oData->findRows($entity, $filter);
	}

	protected function _pushRow(&$row) {
		array_push($this->aRowStack, $this->_row);
		$this->_row = $row;
		#bdump($this->_row, 'push');
		$this->_entity = $this->_row->oEntity;
	}

	protected function _popRow() {
		if ($this->_row->empty) $this->oData->deleteRow($this->_row->oEntity, $this->_row);

		$this->_row = array_pop($this->aRowStack);
		if (!$this->_row) throw new \Exception("row stack rans empty");
		#bdump($this->_row, 'pop');
		$this->_entity = $this->_row->oEntity;
		if (!$this->_entity) throw new \Exception("row entity was empty");
	}

// row funcs
	protected function _set($property, $value = null, $aCfg = null) {
		if (!$this->_row) throw new \Exception("no current row set. use method new...() for creation");
		#bdump($this->_row, $property);
		$this->_property = $this->_row->oEntity->findOrCreateProperty($property, $aCfg);
		if ($this->_property->related) {
			$this->_ref($this->_property->related, $value);
		}
		else $this->_row->$property = $value;
		return $this;
	}

	protected function _get($entity, $value = null) {
		if (is_numeric($value)) return $this->getRows($entity)[$value];
		return $this->getRows($entity, $value)[0];
	}

	protected function _filter($entity, $field, $value) {
		return [[$field => $value]];
	}

// helper

	protected function _invoke($func, $aArg, &$result = null) {
		if (method_exists($this, $func)) {
			$result = call_user_func_array([$this, $func], $aArg);
			return true;
		}
		return false;
	}

	function __call($name, $aArg) {
		if ($name == 'default' && $this->_invoke('_default', $aArg, $ret)) return $ret;
		if ($name == 'new' && $this->_invoke('_new', $aArg, $ret)) return $ret;
		if ($name == 'set' && $this->_invoke('_set', $aArg, $ret)) return $ret;
		if ($name == 'get' && $this->_invoke('_get', $aArg, $ret)) return $ret;

		if (preg_match('~^(get|new|set|add|own|ref|belong|identBy)([A-Z]?\w*?)(By([A-Z]\w*))?$~', $name, $aMatch)) {
			list(, $action, $subject, , $filter) = $aMatch + [null,null,null,null,null];
			#bdump($aMatch, $name);
			if ($filter) {
				$aArg = $this->_filter(lcfirst($subject), lcfirst($filter), $aArg[0]);
			}
			$func = '_'.$action;
			if (method_exists($this, $func)) {
				array_unshift($aArg, lcfirst($subject));
				return call_user_func_array([$this, $func], $aArg);
			}
		}
		throw new \Exception("unknown model command $name");
	}





	protected function _add($entity, $value = null, $property = null) {
		$oEntity = $this->findOrCreateEntity($entity);
		$this->_property = $oEntity->createRelation($this->_entity, $property);
		$property = $this->_property->name;

		if ($value) $row = $this->oData->findOrCreateRow($oEntity, $value);
		else $row = $this->oData->createRow($oEntity, $value);

		$row->$property = $this->_row;
		if (!$value) $this->_pushRow($row);
		return $this;
	}

	protected function _ref($entity, $value = null, $property = null) {
		$oEntity = $this->findOrCreateEntity($entity);
		$this->_property = $this->_entity->createRelation($oEntity, $property);
		$property = $this->_property->name;

		if ($value) $row = $this->oData->findOrCreateRow($oEntity, $value);
		else $row = $this->oData->createRow($oEntity, $value);

		$this->_row->$property = $row;
		if (!$value) $this->_pushRow($row);
		return $this;
	}

	protected function _identBy($property, $value = null, $aCfg = null) {
		$aRow = $this->oData->findRows($this->_row->oEntity, [$property => $value]);
		if (!$aRow) $this->_set($property, $value, $aCfg);
		else {
			$this->_popRow();
			$this->_pushRow($aRow[0]);
		}
		return $this;
	}


	function getData() {
		$aRet = [];
		foreach ($this->oData->aRow as $entity => $aRow) {
			foreach ($aRow as $oRow) {
				if ($oRow->empty) continue;
				$aRet[$entity][] = $oRow->data;
			}
		}
		return $aRet;
	}


}
