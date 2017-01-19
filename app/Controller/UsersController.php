<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public function beforeFilter() {
	parent::beforeFilter();

	if (isset($this->Auth)) {
	    $this->Auth->allow('login', 'logout', 'home', 'activation_login', 'activation_logout' ,'activation_index', 'activation_add', 'activation_edit', 'activation_delete');
	}
    }

    /**
     * User login handling
     * 
     */
    public function login() {

	$this->Cookie = $this->Components->load('Cookie', array('name' => 'login_remember_username'));

	// if current users zero, then automatically redirect to activation page
	$num_users = $this->User->find('count');
	if ($num_users == 0)
	    $this->redirect($this->Auth->redirectUrl(array('controller' => 'activation', 'action' => 'activate')));
	
	if ($this->Auth->loggedIn()) {
	    $this->home();
	}

	if ($this->request->is('post')) {

	    $user = $this->User->findByEmail($this->request->data['User']['username']);

	    if ($this->Auth->login()) {
		$this->redirect($this->Auth->redirectUrl(array('controller' => 'users', 'action' => 'home')));
	    } else {
		$this->setFlashBootstrap(__('Invalid username or password, try again'), 'alert-info');
	    }
	}

	if ($this->Cookie->check('User.username')) {
	    $this->request->data['options']['remember_username'] = 1;
	    $this->request->data['User']['username'] = $this->Cookie->read('User.username');
	}
    }

    public function logout() {
	return $this->redirect($this->Auth->logout());
    }

    /**
     * Redirects the current logged in user to the home page
     *
     */
    public function home() {

	/* Configure::write('Role', array(
	  Configure::write('Role', array(
	  'admin' => 1,
	  'technition' => 2,
	  'supervisor' => 3,
	  'service' => 99
	  )); */
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['service']) {
	    return $this->redirect(array('controller' => 'nodes', 'action' => 'live'));
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['admin']) {
	    return $this->redirect(array('controller' => 'nodes', 'action' => 'live'));
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['technician']) {
	    return $this->redirect(array('controller' => 'nodes', 'action' => 'live'));
	}
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['supervisor']) {
	    return $this->redirect(array('controller' => 'nodes', 'action' => 'live'));
	}
	$this->logout();
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
	$this->User->recursive = 0;
	if ($this->Session->read('Auth.User.role_id') == Configure::read('Role')['service']) {
	    $this->set('users', $this->User->find('all'));
	} else {
	    $this->set('users', $this->User->find('all', array('conditions' => array('User.role_id <>' => '99'))));
	}
	$roles = array_flip(Configure::read('Role'));
	$this->set(compact('roles'));
    }
    
    public function activation_logout(){
	$this->Components->load('MyAppAuth');
	$this->Components->MyAppAuth->logoutSession($this);
	
	return $this->redirect(array('action' => 'login'));
    }
    
    public function activation_index() {

	// do some security checks
	$this->Components->load('MyAppAuth');	
	if ($this->Components->MyAppAuth->checkLoggedInSession($this) == false)
	    throw new BadRequestException();
	$this->Components->MyAppAuth->generateLoggedInSession($this);
	
	$this->User->contain();
	$this->set('users', $this->User->find('all'));	
	$roles = array_flip(Configure::read('Role'));
	$this->set(compact('roles'));
    }
    
    public function activation_add() {

	// do some security checks
	$this->Components->load('MyAppAuth');	
	if ($this->Components->MyAppAuth->checkLoggedInSession($this) == false)
	    throw new BadRequestException();
	
	$this->Components->MyAppAuth->generateLoggedInSession($this);
	
	if ($this->request->is('post')) {
	    $this->User->Behaviors->attach('Tools.Passwordable', array('allowEmpty' => true, 'require' => false, 'confirm' => false));

	    $this->User->create();
	    if ($this->User->save($this->request->data)) {
		$this->Session->setFlash(__('The user has been saved.'));
		return $this->redirect(array('action' => 'activation_index'));
	    } else {
		$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
	    }
	}
	$roles = array_flip(Configure::read('Role'));
	$this->set(compact('roles'));
    }

    public function activation_edit($id) {

	// do some security checks
	$this->Components->load('MyAppAuth');	
	if ($this->Components->MyAppAuth->checkLoggedInSession($this) == false)
	    throw new BadRequestException();
	$this->Components->MyAppAuth->generateLoggedInSession($this);

	if ($this->request->is(array('post', 'put'))) {
	    $this->User->Behaviors->attach('Tools.Passwordable', array('allowEmpty' => true, 'require' => false, 'confirm' => false));

	    if ($this->User->save($this->request->data)) {
		$this->Session->setFlash(__('The user has been saved.'));
		return $this->redirect(array('action' => 'activation_index'));		
	    } else {
		$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
	    }
	} else {
	    $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
	    $this->request->data = $this->User->find('first', $options);
	}

	$roles = array_flip(Configure::read('Role'));
	$this->set(compact('roles'));
    }

    public function activation_delete($id) {

	// do some security checks
	$this->Components->load('MyAppAuth');	
	if ($this->Components->MyAppAuth->checkLoggedInSession($this) == false)
	    throw new BadRequestException();
	$this->Components->MyAppAuth->generateLoggedInSession($this);

	$this->delete($id, array('action' => 'activation_index'));
    }
    
    

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
	if (!$this->User->exists($id)) {
	    throw new NotFoundException(__('Invalid user'));
	}
	$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
	$this->set('user', $this->User->find('first', $options));
	$roles = array_flip(Configure::read('Role'));
	$this->set(compact('roles'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($redirect_url = null, $show_all_usertypes = false) {
	if ($this->request->is('post')) {
	    $this->User->Behaviors->attach('Tools.Passwordable', array('allowEmpty' => true, 'require' => false, 'confirm' => false));

	    // extra check against super admin priv
	    if ($this->Session->read('Auth.User.role_id') != Configure::read('Role')['service']) {
		if ($this->request->data['User']['role_id'] == '99')
		    $this->request->data['User']['role_id'] = '1';
	    }

	    $this->User->create();
	    if ($this->User->save($this->request->data)) {
		$this->Session->setFlash(__('The user has been saved.'));
		if ($redirect_url == null)
		    return $this->redirect(array('action' => 'index'));
		else
		    return $this->redirect($redirect_url);
	    } else {
		$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
	    }
	}
	$roles = array_flip(Configure::read('Role'));
	if ($this->Session->read('Auth.User.role_id') != Configure::read('Role')['service']) {
	    unset($roles['99']); // remove super admin
	}
	$this->set(compact('roles'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null, $redirect_url = null) {
	if (!$this->User->exists($id)) {
	    throw new NotFoundException(__('Invalid user'));
	}

	if ($this->request->is(array('post', 'put'))) {
	    $this->User->Behaviors->attach('Tools.Passwordable', array('allowEmpty' => true, 'require' => false, 'confirm' => false));

	    if ($this->Session->read('Auth.User.role_id') != Configure::read('Role')['service']) {
		// extra check against super admin priv
		if ($this->request->data['User']['role_id'] == '99')
		    $this->request->data['User']['role_id'] = '1';
	    }

	    if ($this->User->save($this->request->data)) {
		$this->Session->setFlash(__('The user has been saved.'));
		if ($redirect_url == null)
		    return $this->redirect(array('action' => 'index'));
		else
		    return $this->redirect($redirect_url);
	    } else {
		$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
	    }
	} else {
	    $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
	    $this->request->data = $this->User->find('first', $options);
	}

	$roles = array_flip(Configure::read('Role'));
	if ($this->Session->read('Auth.User.role_id') != Configure::read('Role')['service']) {
	    unset($roles['99']); // remove super admin
	}
	$this->set(compact('roles'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null, $redirect_url = null) {
	$this->User->id = $id;
	if (!$this->User->exists()) {
	    throw new NotFoundException(__('Invalid user'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->User->delete()) {
	    $this->Session->setFlash(__('The user has been deleted.'));
	} else {
	    $this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
	}
	if ($redirect_url == null)
	    return $this->redirect(array('action' => 'index'));
	else
	    return $this->redirect($redirect_url);
    }

    /**
     * Gives the user the ability to enter an email address
     * A password is generated and sent in the email
     */
    public function lost_password() {

	if ($this->request->is('post')) {

	    $newpass = $this->User->generateAndSaveNewPassword(array('conditions' => array('User.email' => $this->request->data['User']['email'])));

	    if ($newpass != false) {
		$this->Session->setFlash(__('Recovery email sent.'));
		$this->User->sendNewPasswordThroughEmail($this->request->data['User']['email'], $newpass);
		return $this->redirect(array('controller' => 'users', 'action' => 'login'));
	    } else {
		$this->Session->setFlash(__('The email address you entered does not correspond to a user on our system.'));
	    }
	}
    }

    /*
     * Enable the user to change their password
     */

    public function change_password() {

	if ($this->request->is('post') | $this->request->is('put')) {

	    // attach the behavior and force the user to enter the current password for security purposes            
	    $this->User->Behaviors->attach('Tools.Passwordable', array('current' => true));
	    $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
	    if ($this->User->save($this->request->data)) {
		$this->Session->setFlash(__('Successfully changed password'));
		return $this->redirect(array('controller' => 'users', 'action' => 'home'));
	    }
	    $this->Session->setFlash(__('Could not change password'));

	    // pw should not be passed to the view again for security reasons
	    unset($this->request->data['User']['current']);
	    unset($this->request->data['User']['pwd']);
	    unset($this->request->data['User']['pwd_repeat']);
	}
    }

}
