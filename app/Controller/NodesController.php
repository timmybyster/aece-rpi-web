<?php

App::uses('AppController', 'Controller');
App::uses('AMULib', 'AjaxMultiUpload.Lib');

/**
 * Nodes Controller
 *
 * @property Node $Node
 * @property PaginatorComponent $Paginator
 */
class NodesController extends AppController {

    public function beforeFilter() {
	parent::beforeFilter();

	if (isset($this->Security)) {
	    $this->Security->unlockedActions = array('get_data', 'get_all_data', 'update_position');
	}
    }

    public function config_warning(){
	if ($this->request->is('post')) {
	    $this->loadModel('SystemSetting');
	    $delay = $this->request->data['WarningConfig']['dismiss_delay'];	    
	    $this->SystemSetting->setWarningDismissDelay($delay);
	    /*$background_image_contrast = $this->request->data['BackgroundConfig']['background_image_contrast'];	    
	    $this->SystemSetting->setBackgroundImageContrast($background_image_contrast);
	    $background_image_size_multiply = $this->request->data['BackgroundConfig']['background_image_size_multiply'];	    
	    $this->SystemSetting->setBackgroundImageSizeMultiply($background_image_size_multiply);*/
	    
	    $this->Session->setFlash(__('Warning delay updated'), 'default', array('class' => 'alert alert-success'));
	    return $this->redirect(array('action' => 'config'));
	}
    }
    
    public function config_backgroundimg(){
	if ($this->request->is('post')) {
	    $this->loadModel('SystemSetting');	   
	    $background_image_contrast = $this->request->data['BackgroundConfig']['background_image_contrast'];	    
	    $this->SystemSetting->setBackgroundImageContrast($background_image_contrast);
	    $background_image_size_multiply = $this->request->data['BackgroundConfig']['background_image_size_multiply'];	    
	    $this->SystemSetting->setBackgroundImageSizeMultiply($background_image_size_multiply);
	    
	    $this->Session->setFlash(__('Background image settings updated'), 'default', array('class' => 'alert alert-success'));
	    return $this->redirect(array('action' => 'config'));
	}	
    }
    
    public function config() {
	
	$this->loadModel('SystemSetting');
	$this->AjaxMultiUpload = $this->Components->load('AjaxMultiUpload.Upload');
	$this->AjaxMultiUpload->startup($this);
	
	$warning_dismiss_delay = $this->SystemSetting->getWarningDismissDelay();
	$background_image_contrast = $this->SystemSetting->getBackgroundImageContrast();
	$background_image_size_multiply = $this->SystemSetting->getBackgroundImageSizeMultiply();
	
	$this->set(compact('warning_dismiss_delay', 'period_id', 'background_image_contrast', 'background_image_size_multiply'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
	$this->Node->contain('Parent.serial');
	$nodes = $this->Node->find('all');
	if ($nodes != null)
	    $nodes = $this->Node->populateEnums($nodes);
	$types = $this->Node->types();
	$this->set(compact('nodes', 'types'));
    }

    public function live($focus_node_id = null) {

	// user image configuration
	$backgroundImg = AMULib::oneFilePathLocation('statusPage', 'background');
	$bc1Img = AMULib::oneFilePathLocation('statusPage', 'BC-1');
	$isc1Img = AMULib::oneFilePathLocation('statusPage', 'ISC-1');
	$ib651Img = AMULib::oneFilePathLocation('statusPage', 'IB651');
	$keySwitchArmedImg = AMULib::oneFilePathLocation('statusPage', 'KeySwitchArmed');
	$keySwitchDisarmedImg = AMULib::oneFilePathLocation('statusPage', 'KeySwitchDisarmed');
	$detonatorConnImg = AMULib::oneFilePathLocation('statusPage', 'DetonatorConnected');
	$detonatorNotConnImg = AMULib::oneFilePathLocation('statusPage', 'DetonatorNotConnected');
	$faultDisplayImg = AMULib::oneFilePathLocation('statusPage', 'FaultDisplay');
	$warningImg = AMULib::oneFilePathLocation('statusPage', 'Warning');
	$ib651LedImg = AMULib::oneFilePathLocation('statusPage', 'IB651Led');
	$isc1LedImg = AMULib::oneFilePathLocation('statusPage', 'ISC1Led');

	$mayMoveNodes = true;
	if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['technician']) {
	    $mayMoveNodes = false;
	}
	
	$this->loadModel('SystemSetting');
	$background_image_contrast = $this->SystemSetting->getBackgroundImageContrast();
	$background_image_size_multiply = $this->SystemSetting->getBackgroundImageSizeMultiply();
	$warning_dismiss_delay = $this->SystemSetting->getWarningDismissDelay();
	
	$disable_footer = true;
	$this->set(compact('disable_footer', 'backgroundImg', 'bc1Img', 'isc1Img', 'ib651Img', 'keySwitchArmedImg', 'keySwitchDisarmedImg', 'faultDisplayImg','warningImg', 'frontPanelImg',
		'ib651LedImg', 'isc1LedImg', 'detonatorConnImg', 'detonatorNotConnImg', 'warning_dismiss_delay', 'mayMoveNodes', 'background_image_contrast', 'background_image_size_multiply', 'focus_node_id'));
    }

    public function get_data() {
	$this->autoRender = false; // no view to render	
	$this->response->type('json');

	$node = $this->Node->findById($this->request->data['id']);
	if ($node != null) {
	    //$ret['val1'] = 555;//$node['Nodes']['val1'];
	    $ret['val1'] = $node['Node']['temperature'];
	    //$ret['val1'] = 555;
	    $this->response->body(json_encode($ret));
	    return;
	}

	$ret['val1'] = 333;
	$this->response->body(json_encode($ret));
    }

    public function get_all_data() {
	$this->autoRender = false; // no view to render	
	$this->response->type('json');

	$conditions = array();
	if (isset($this->request->data['updated_after'])){	    
	    //$conditions = array('modified >'=> date('Y-m-d H:i:s', strtotime('-1 second')));	
	    $conditions = array('modified >'=> $this->request->data['updated_after']);
	    //$conditions = array('modified >'=> "2015-02-12 16:09:43");
	}
	
	$this->Node->contain();
	$nodes = $this->Node->find('all', array('conditions' => $conditions));
	if ($nodes != null) {
	    $nodes = $this->Node->populateEnums($nodes);
	    $this->response->body(json_encode($nodes));
	    return;
	}
	$ret['error'] = "No Data";
	$this->response->body(json_encode($ret));
    }

    public function update_position() {
	$this->autoRender = false; // no view to render	
	$this->response->type('json');

	if (!$this->Node->exists($this->request->data['id'])) {
	    $ret['success'] = 0;
	    $ret['reason'] = "Id does not exist";
	}

	if ($this->Node->save($this->request->data)) {
	    $ret['success'] = 1;
	} else {
	    $ret['success'] = 0;
	    $ret['reason'] = "Other";
	}

	$this->response->body(json_encode($ret));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
	if (!$this->Node->exists($id)) {
	    throw new NotFoundException(__('Invalid node'));
	}
	$options = array('conditions' => array('Node.' . $this->Node->primaryKey => $id));
	$this->set('node', $this->Node->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
	if ($this->request->is('post')) {
	    $this->Node->create();
	    if ($this->Node->save($this->request->data)) {
		$this->Session->setFlash(__('The node has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The node could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
	if (!$this->Node->exists($id)) {
	    throw new NotFoundException(__('Invalid node'));
	}
	if ($this->request->is(array('post', 'put'))) {
	    if ($this->Node->save($this->request->data)) {
		$this->Session->setFlash(__('The node has been saved.'), 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('action' => 'index'));
	    } else {
		$this->Session->setFlash(__('The node could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	    }
	} else {
	    $options = array('conditions' => array('Node.' . $this->Node->primaryKey => $id));
	    $this->request->data = $this->Node->find('first', $options);
	}

	$parents = $this->Node->find('list');
	$types = Node::types();
	$key_switch_statuses = Node::key_switch_enum();
	$communication_statuses  = Node::communication_status_enum();
	$blast_armed_enums = Node::blast_armed_enum();
	$detonator_statuses = Node::detonator_status_enum();
	$partial_blast_lfs_enum = Node::partial_blast_lfs_enum();
	$full_blast_lfs_enum = Node::full_blast_lfs_enum();
	$booster_fired_lfs_enum = Node::booster_fired_lfs_enum();
	$missing_pulse_detected_lfs_enum = Node::missing_pulse_detected_lfs_enum();
	$cable_fault_enum = Node::cable_fault_enum();
	$earth_leakage_enum = Node::earth_leakage_enum();
	$isc_key_switch_enum = Node::isc_key_switch_enum();
	$fire_button_enum = Node::fire_button_enum();
	$isolation_status_enum = Node::isolation_status_enum();
	
	$this->set(compact('parents', 'types', 'key_switch_statuses', 'communication_statuses', 'blast_armed_enums', 'detonator_statuses', 'partial_blast_lfs_enum', 'full_blast_lfs_enum', 'booster_fired_lfs_enum', 'missing_pulse_detected_lfs_enum', 'cable_fault_enum', 'earth_leakage_enum','isc_key_switch_enum','fire_button_enum','isolation_status_enum'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
	$this->Node->id = $id;
	if (!$this->Node->exists()) {
	    throw new NotFoundException(__('Invalid node'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Node->delete()) {
	    $this->Session->setFlash(__('The node has been deleted.'), 'default', array('class' => 'alert alert-success'));
	} else {
	    $this->Session->setFlash(__('The node could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
	}
	return $this->redirect(array('action' => 'index'));
    }

}
