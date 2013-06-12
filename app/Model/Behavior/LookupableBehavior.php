<?php
/**
 * Lookupable Behavior
 *
 * @package greyback_core
 * @subpackage greyback_core.models.behaviors
 */
class LookupableBehavior extends ModelBehavior {

/**
 * A generic function that simply returns the value of a given $field for an record
 * that matches the given $conditions. If $create is set to true and no record matching
 * the conditions can be found, it will be created automatically.
 *
 * @param unknown_type $conditions
 * @param unknown_type $field
 * @param unknown_type $create
 * @return unknown
 * @access public
 */
	function lookup(&$model, $conditions, $field = 'id', $create = true) {
		if (!is_array($conditions)) {
			$conditions = array($model->displayField => $conditions);
		}

		if (!empty($field)) {
			$fieldValue = $model->field($field, $conditions);
		} else {
			$fieldValue = $model->find($conditions);
		}
		if ($fieldValue !== false) {
			return $fieldValue;
		}
		if (!$create) {
			return false;
		}
		$model->create($conditions);
		if (!$model->save()) {
			return false;
		}
		$conditions[$model->primaryKey] = $model->id;
		if (empty($field)) {
			return $model->read();
		}
		return $model->field($field, $conditions);
	}
}
?>