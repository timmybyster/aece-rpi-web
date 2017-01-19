<?php

App::uses('AppController', 'Controller');

/**
 * PostBlastErrors Controller
 *
 * @property PostBlastError $PostBlastError
 * @property PaginatorComponent $Paginator
 */
class PostBlastErrorsController extends AppController {

    /**
     * Components
     *
     * @var array
     */


    /**
     * index method
     *
     * @return void
     */
    public function index() {
	// find the last blast time
	$this->PostBlastError->BlastEvent->contain();
	$last_blast_event = $this->PostBlastError->BlastEvent->find('first', array('order'=>'BlastEvent.blast_time DESC'));
	$last_blast_time = $last_blast_event['BlastEvent']['blast_time'];
	
	// display all errors with this time	
	$this->PostBlastError->contain('BlastEvent.blast_time');	
	$postBlastErrors = $this->PostBlastError->findAllByBlastEventId($last_blast_event['BlastEvent']['id']);
	$postBlastErrors = $this->PostBlastError->populateNodeIds($postBlastErrors);
	$this->set(compact('postBlastErrors', 'last_blast_time'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
	if (!$this->PostBlastError->exists($id)) {
	    throw new NotFoundException(__('Invalid post blast error'));
	}
	$options = array('conditions' => array('PostBlastError.' . $this->PostBlastError->primaryKey => $id));
	$this->set('postBlastError', $this->PostBlastError->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
	if ($this->request->is('post')) {
	    $this->PostBlastError->create();
	    if ($this->PostBlastError->save($this->request->data)) {
		$this->Session->setFlash(__('The post blast error has been saved.'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The post blast error could not be saved. Please, try again.'));
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
	if (!$this->PostBlastError->exists($id)) {
	    throw new NotFoundException(__('Invalid post blast error'));
	}
	if ($this->request->is(array('post', 'put'))) {
	    if ($this->PostBlastError->save($this->request->data)) {
		$this->Session->setFlash(__('The post blast error has been saved.'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The post blast error could not be saved. Please, try again.'));
	    }
	} else {
	    $options = array('conditions' => array('PostBlastError.' . $this->PostBlastError->primaryKey => $id));
	    $this->request->data = $this->PostBlastError->find('first', $options);
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
	$this->PostBlastError->id = $id;
	if (!$this->PostBlastError->exists()) {
	    throw new NotFoundException(__('Invalid post blast error'));
	}
	$this->request->allowMethod('post', 'delete');
	if ($this->PostBlastError->delete()) {
	    $this->Session->setFlash(__('The post blast error has been deleted.'));
	} else {
	    $this->Session->setFlash(__('The post blast error could not be deleted. Please, try again.'));
	}
	return $this->redirect(array('action' => 'index'));
    }

}
