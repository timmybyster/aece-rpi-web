<?php

App::uses('AppModel', 'Model');

/**
 * Warning Model
 *
 * @property User $User
 */
class Warning extends AppModel {
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    public function acknowledgeWarning($id, $user_id){
	if ($this->read(null, $id) == 'false')
            return;        
	$this->saveField('user_id', $user_id);
	$this->saveField('acknowledged', '1');
	return true;
    }
    
    /**
     * belongsTo associations
     * 
     * @var array
     */
    public $belongsTo = array(
	'User' => array(
	    'className' => 'User',
	    'foreignKey' => 'user_id',
	    'conditions' => '',
	    'fields' => '',
	    'order' => ''
	)
    );

}
