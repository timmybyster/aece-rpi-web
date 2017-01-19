<?php

App::uses('AppModel', 'Model');

/**
 * PostBlastError Model
 *
 * @property BlastEvent $BlastEvent
 */
class PostBlastError extends AppModel {
    /*
     * Given array of PostBlastErrors, populate the node_id if it exists by checking the Node_serial field
     */

    public function populateNodeIds($errors) {
	$newdata = array();

	// get list of all the serials
	$serials = Hash::extract($errors, '{n}.PostBlastError.node_serial');
	$Node = ClassRegistry::init('Node');
	$nodes = $Node->find('list', array('fields' => array('Node.id', 'Node.serial'), 'conditions' => array('Node.serial' => $serials)));
	foreach ($errors as $error) {
	    $error['PostBlastError']['node_id'] = array_search($error['PostBlastError']['node_serial'], $nodes);
	    array_push($newdata, $error);
	}
	return $newdata;
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
	'BlastEvent' => array(
	    'className' => 'BlastEvent',
	    'foreignKey' => 'blast_event_id',
	    'conditions' => '',
	    'fields' => '',
	    'order' => ''
	)
    );

}
