<?php
App::uses('Model', 'Model');
class AppModel extends Model {
	var $actsAs = array('Containable', 'Lookupable');
	
	public function find($type, $options = array(), $order = null, $recursive = null) {
		switch ($type) {
			case 'concatlist':
				if(!isset($options['fields']) || count($options['fields']) < 3) {
					return parent::find('list', $options);
				}

				if(!isset($options['separator'])) {
					$options['separator'] = ' ';
				}

				$options['recursive'] = -1;
				$list = parent::find('all', $options);

				for($i = 1; $i < count($options['fields']); $i++) {
					$field[$i] = str_replace($this->alias.'.', '', $options['fields'][$i]);
				}
				
				switch(count($options['fields'])) {
					case 4:
						return Set::combine($list, '{n}.'.$this->alias.'.'.$this->primaryKey,
							array('%s'.$options['separator'][0].'%s'.$options['separator'][1].'%s',
							'{n}.'.$this->alias.'.'.$field[1],
							'{n}.'.$this->alias.'.'.$field[2],
							'{n}.'.$this->alias.'.'.$field[3]));
						break;
					default:
						return Set::combine($list, '{n}.'.$this->alias.'.'.$this->primaryKey,
							array('%s'.$options['separator'].'%s',
							'{n}.'.$this->alias.'.'.$field[1],
							'{n}.'.$this->alias.'.'.$field[2]));
						break;
				}					
				break;

			case 'matches':
            	if (!isset ($options['joins'])) {
                	$options['joins'] = array ();
				}
                $conditions = isset ($options['operation']) ? $options['operation'] : 'all';

                if (!isset ($options['model']) or !isset ($options['scope'])) {
                	break;
				}
                // hack to filter over several HABTM tables
                $model_list = (is_array ($options['model']) ? $options['model'] : array ($options['model']));
                foreach ($model_list as $model) {
					$scope = ((sizeof ($model_list) > 1 and isset ($options['scope'][$model])) ? $options['scope'][$model] : $options['scope']);
					$assoc = $this->hasAndBelongsToMany[$model];
					$bind = "{$assoc['with']}.{$assoc['foreignKey']} = {$this->alias}.{$this->primaryKey}";
					$options['joins'][] = array (
						'table'         => $assoc['joinTable'],
						'alias'         => $assoc['with'],
						'type'          => 'inner',
						'foreignKey'    => false,
						'conditions'    => array ($bind)
					);

					$bind = $model . '.' . $this->{$model}->primaryKey . ' = ';
					$bind .= "{$assoc['with']}.{$assoc['associationForeignKey']}";
					$options['joins'][] = array (
						'table'         => $this->{$model}->table,
						'alias'         => $model,
						'type'          => 'inner',
						'foreignKey'    => false,
						'conditions'    => array_merge (array ($bind), (array) $scope)
					);
                }

                unset ($options['model'], $options['scope']);
                return parent::find ($conditions, $options, $order, $recursive);
               	break;

			default:
				return parent::find($type, $options);
				break;
		}
	}
}
