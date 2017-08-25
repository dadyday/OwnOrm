<?php
namespace OwnOrm;

use Nette;

class Data {
	use Nette\SmartObject;

	var
		$oModel = null,
		$aRow = [];

	function __construct(Model $oModel, $aData = []) {
		$this->oModel = $oModel;
	}

	function createRow($entity, $aData = [], $aCfg = []) {
		$oEntity = $this->oModel->findOrCreateEntity($entity, $aCfg);
		$oRow = $oEntity->createRow($this, $aData);
		$this->aRow[$oEntity->_name][] = $oRow;
		return $oRow;
	}

	function getRows($entity) {
		if (!$oEntity = $this->oModel->findEntity($entity)) return [];
		$aRow = isset($this->aRow[$oEntity->_name]) ? $this->aRow[$oEntity->_name] : [];
		return $aRow;
	}

	function findRows($entity, $aFilter = null, $limit = 0) {
		$oEntity = $this->oModel->findEntity($entity);
		if (!is_array($aFilter)) {
			$aField = $oEntity->getIdentityFields();
			$aFilter = array_combine($aField, [$aFilter]);
		}
		$aRow = [];
		foreach ($this->getRows($oEntity->_name) as $oRow) {
			$ok = true;
			foreach ($aFilter as $field => $value) {
				$ok &= isset($oRow->$field) && $oRow->$field == $value;
				#bdump([$oRow->$field, $value, $ok], $entity);
				if (!$ok) break;
			}
			if ($ok) {
				$aRow[] = $oRow;
				if (!--$limit) break;
			}
		}
		return $aRow;
	}

	function getRow($entity, $id) {
		if (is_numeric($id)) return $this->getRows($entity)[$id];
		throw new \Exception("unknown value type $id");
	}

	function findOrCreateRow($entity, $aData = null) {
		if (is_a($aData, __NAMESPACE__.'\Row')) return $aData;
		$oEntity = $this->oModel->findEntity($entity);
		$aRow = empty($aData) ? null : $this->findRows($entity, $aData, 1);
		if (!empty($aRow)) return $aRow[0];
		return $this->createRow($entity, $aData);
	}

	function deleteRow($entity, $row) {
		if (is_a($row, __NAMESPACE__.'\Row')) {
			$entity = $row->oEntity->_name;
			$row = array_search($row, $this->aRow[$entity]);
		}

		$oRow = null;
		if (is_numeric($row)) {
			if (isset($this->aRow[$entity][$row])) {
				$oRow = $this->aRow[$entity][$row];
				array_splice($this->aRow[$entity], $row, $row);
			}
		}
		return $oRow;
	}
}
