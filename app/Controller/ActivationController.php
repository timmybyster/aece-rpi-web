<?php

App::uses('AppController', 'Controller');

/**
 * Warnings Controller
 *
 * @property Warning $Warning
 * @property PaginatorComponent $Paginator
 */
class ActivationController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public function beforeFilter() {
	parent::beforeFilter();

	if (isset($this->Auth)) {
	    $this->Auth->allow('activate');
	}
    }

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array();

    /**
     * index method
     *
     * @return void
     */
    public function activate() {
	
	$keylen = 5;
	
	$secret2 = "234lkjh2355523345lkjh345lKHWRE";
		
	if ($this->request->is('post')) {

	    $secret = "234lkjh234l123345lkjh345lKHWRE";
	    
	    $client_key = $this->request->data['Activation']['client_key'];
	    $activate_code = $this->request->data['Activation']['activation_code'];
	       	    
	    $hash_input = $client_key.$secret;
	    $gen_hash = hash("sha512", $hash_input);
	    
	    // check if somebody has not changed the code
	    $client_key_protect = $this->request->data['Activation']['client_key_protect'];
	    $gen_hash_ck = hash("sha512", $client_key.$secret2);	   
	    /*if ($gen_hash_ck != $client_key_protect){
		echo "edited!!";
		echo $gen_hash_ck;
	    }*/
	    
	    if (substr($gen_hash, 0, 5) == $activate_code && $gen_hash_ck == $client_key_protect) {
		$this->Components->load('MyAppAuth');
		$this->Components->MyAppAuth->generateLoggedInSession($this);
		$this->redirect($this->Auth->redirectUrl(array('controller' => 'users', 'action' => 'activation_index')));
	    } else {
		$client_key = substr(str_shuffle(md5(time())),0,$keylen);
		$client_key_protect = hash("sha512", $client_key.$secret2);
		$this->set(compact('client_key','client_key_protect'));
		
		$this->setFlashBootstrap(__('Invalid activation code. Please try again'), 'alert-info');
	    }
	} else {
	    $client_key = substr(str_shuffle(md5(time())),0,$keylen);	    
	    $client_key_protect = hash("sha512", $client_key.$secret2);
	    $this->set(compact('client_key','client_key_protect'));
	}
	
    }

}
