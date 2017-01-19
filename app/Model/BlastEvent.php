<?php
App::uses('AppModel', 'Model');
/**
 * BlastEvent Model
 *
 * @property PostBlastError $PostBlastError
 */
class BlastEvent extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'blast_time';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PostBlastError' => array(
			'className' => 'PostBlastError',
			'foreignKey' => 'blast_event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
