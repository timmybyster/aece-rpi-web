<?php

App::uses('AppController', 'Controller');
App::uses('DataTableRequestHandlerTrait', 'DataTable.Lib');

/**
 * Warnings Controller
 *
 * @property Warning $Warning
 * @property PaginatorComponent $Paginator
 */
class WarningsController extends AppController {
    
    use DataTableRequestHandlerTrait;
    /**
     * Components
     *
     * @var array
     */
     public $components = [
        'DataTable.DataTable' => [	    
            'Default' => [
		'model' => 'Warning',
		'contain' => ['User.email'],
		'autoData' => true,
		'autoRender' => true,
                'columns' => [     
		    'message',
		    'acknowledged' => [
                        'bSearchable' => false,
                    ],
		    'User.email' => 'User',
		    'created',
		    'modified'
                ]
            ],
        ],
    ];
    
    public $helpers = [
        'DataTable.DataTable',
    ];

    public function beforeFilter() {
	parent::beforeFilter();

	if (isset($this->Security)) {
	    $this->Security->unlockedActions = array('get_unacknowledged', 'acknowledge_warning');
	}
    }

    // called through ajax to get all the unacknowleged warnings
    public function get_unacknowledged() {
	$this->autoRender = false; // no view to render	
	$this->response->type('json');
	$this->Warning->contain();
	$warnings = $this->Warning->findAllByAcknowledged('');
	$this->response->body(json_encode($warnings));
    }

    // only admins may acknowledge a warning
    public function acknowledge_warning() {
	$this->autoRender = false; // no view to render	
	$this->response->type('json');

	$this->Warning->id = $this->request->data['id'];
	if (!$this->Warning->exists()) {
	    $ret["success"] = 0;
	    $ret["reason"] = "Warning with id " . $this->request->data['id'] . " does not exist";
	    $this->response->body(json_encode($ret));
	    return;
	}

	if ($this->Session->read('Auth.User.role_id') != Configure::read('Role')['service'] 
		&& $this->Session->read('Auth.User.role_id') != Configure::read('Role')['admin']
		&&  $this->Session->read('Auth.User.role_id') != Configure::read('Role')['supervisor']) {
	    
	    $ret["success"] = 0;
	    $ret["reason"] = "You need to be an Admin or Supervisor user to acknowledge this warning";
	    $this->response->body(json_encode($ret));
	    return;
	}


	if ($this->Warning->acknowledgeWarning($this->request->data['id'], $this->Session->read('Auth.User.id'))) {
	    $ret["success"] = 1;
	} else {
	    $ret["success"] = 0;
	    $ret["reason"] = "Could not update warning status";
	}

	$this->response->body(json_encode($ret));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
	$this->DataTable->setViewVar('Default');
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
	if (!$this->Warning->exists($id)) {
	    throw new NotFoundException(__('Invalid warning'));
	}
	$options = array('conditions' => array('Warning.' . $this->Warning->primaryKey => $id));
	$this->set('warning', $this->Warning->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
	if ($this->request->is('post')) {
	    $this->Warning->create();
	    if ($this->Warning->save($this->request->data)) {
		$this->Session->setFlash(__('The warning has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The warning could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	    }
	}
	$users = $this->Warning->User->find('list');
	$this->set(compact('users'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
	if (!$this->Warning->exists($id)) {
	    throw new NotFoundException(__('Invalid warning'));
	}
	if ($this->request->is(array('post', 'put'))) {
	    if ($this->Warning->save($this->request->data)) {
		$this->Session->setFlash(__('The warning has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The warning could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	    }
	} else {
	    $options = array('conditions' => array('Warning.' . $this->Warning->primaryKey => $id));
	    $this->request->data = $this->Warning->find('first', $options);
	}
	$users = $this->Warning->User->find('list');
	$this->set(compact('users'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
	$this->Warning->id = $id;
	if (!$this->Warning->exists()) {
	    throw new NotFoundException(__('Invalid warning'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Warning->delete()) {
	    $this->Session->setFlash(__('The warning has been deleted.'), 'default', array('class' => 'alert alert-success'));
	} else {
	    $this->Session->setFlash(__('The warning could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	}
	return $this->redirect(array('action' => 'index'));
    }

}
