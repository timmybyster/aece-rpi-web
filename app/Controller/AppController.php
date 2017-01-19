<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
	'Session',
	'Auth' => array(
	    'loginAction' => array('controller' => 'users', 'action' => 'login'),
	    'loginRedirect' => array('controller' => 'users', 'action' => 'home', 'base' => false),
	    'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
	    'authorize' => array('Tools.Tiny')),
	'RequestHandler', //AJAX calls
	'DebugKit.Toolbar',
	'Security'
    );
    var $helpers = array(
	'Form' => array('className' => 'BoostCake.BoostCakeForm'),
	'Js',
	'AssetCompress.AssetCompress');

    function beforeFilter() {
	$this->layout = 'bootstrap';

	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['service']){
	    $this->Auth->allow();
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['admin']){
	    $this->Auth->allow();
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['supervisor']){
	    $this->Auth->allow();
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['technician']){
	    $this->Auth->allow();
	}
    }

    // Maybe better place to do this?
    // Display flash message in bootstrap style
    // $class can be 'alert-success', 'alert-info', 'alert-warning', 'alert-danger'
    public function setFlashBootstrap($mesg, $class) {
	$this->Session->setFlash(__($mesg), 'alert', array(
	    'plugin' => 'BoostCake',
	    'class' => $class
	));
    }

}
