<?php

App::uses('AppController', 'Controller');
App::uses('DataTableRequestHandlerTrait', 'DataTable.Lib');

/**
 * Logs Controller
 *
 * @property Log $Log
 * @property PaginatorComponent $Paginator
 */
class LogsController extends AppController {

    use DataTableRequestHandlerTrait;
    /**
     * Components
     *
     * @var array
     */
     public $components = [
        'DataTable.DataTable' => [	    
            'Default' => [
		'model' => 'Log',
		'autoData' => true,
		'autoRender' => true,
                'columns' => [     
		    'message',
		    'node_serial',
		    'created'
                ]
            ],
        ],
    ];
    
    public $helpers = [
        'DataTable.DataTable',
    ];
    
    public function config() {

	$this->loadModel('SystemSetting');
	if ($this->request->is('post')) {

	    $period = $this->request->data['LogsConfig']['period_id'];
	    
	    $this->SystemSetting->setLogPeriod($period);
	    $this->Session->setFlash(__('Log configuration been saved.'), 'default', array('class' => 'alert alert-success'));
	    return $this->redirect(array('action' => 'index'));
	}

	$period_id = $this->SystemSetting->getLogPeriod();	
	$periods = $this->SystemSetting->logTimeToKeepPeriods();
	$this->set(compact('periods', 'period_id'));
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
	if (!$this->Log->exists($id)) {
	    throw new NotFoundException(__('Invalid log'));
	}
	$options = array('conditions' => array('Log.' . $this->Log->primaryKey => $id));
	$this->set('log', $this->Log->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
	if ($this->request->is('post')) {
	    $this->Log->create();
	    if ($this->Log->save($this->request->data)) {
		$this->Session->setFlash(__('The log has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The log could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	    }
	}
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
	if (!$this->Log->exists($id)) {
	    throw new NotFoundException(__('Invalid log'));
	}
	if ($this->request->is(array('post', 'put'))) {
	    if ($this->Log->save($this->request->data)) {
		$this->Session->setFlash(__('The log has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The log could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	    }
	} else {
	    $options = array('conditions' => array('Log.' . $this->Log->primaryKey => $id));
	    $this->request->data = $this->Log->find('first', $options);
	}
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
	$this->Log->id = $id;
	if (!$this->Log->exists()) {
	    throw new NotFoundException(__('Invalid log'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Log->delete()) {
	    $this->Session->setFlash(__('The log has been deleted.'), 'default', array('class' => 'alert alert-success'));
	} else {
	    $this->Session->setFlash(__('The log could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	}
	return $this->redirect(array('action' => 'index'));
    }

}
