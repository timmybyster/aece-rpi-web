<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $displayField = "email";
    
    public $virtualFields = array(
	'username' => 'User.email'
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        // company does not exist in the database and is only used for form validation on user registration
        'email' => array(
            'unique' => array(
                'rule' => array('isUnique'),
                'message' => 'User with this email already exists',
            )    
	)

    );
}
