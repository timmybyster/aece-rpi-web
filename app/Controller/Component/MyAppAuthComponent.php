<?php

App::uses('Component', 'Controller');
App::uses('Component', 'Session');

/*
 *
 * This component checks that the header contains the correct hash, otherwise it exists and returns a default empty JSON object
 * 
 * At the start of your controller action, do this: 
 * $this->Components->load('MyAppAuth')->startup($this);
 * 
 */

class MyAppAuthComponent extends Component {

    public function __construct(ComponentCollection $collection, $settings) {
	
    }

    public function generateHash($date) {
	$secret = "234lkjh234lkjh345lkjh345lKHWRE";
	$hash_input = $secret . $date;
	$gen_hash = hash("sha512", $hash_input);
	return $gen_hash;
    }

    public function generateLoggedInSession(Controller $controller) {
	$date = date('c');
	$hash = $this->generateHash($date);
	$controller->Session->write('hash', $hash);
	$controller->Session->write('date', $date);
    }

    public function logoutSession(Controller $controller) {
	$controller->Session->delete('hash');
	$controller->Session->delete('date');
    }

    public function checkLoggedInSession(Controller $controller) {

	// check security	
	$hash = $controller->Session->read('hash');
	$date = $controller->Session->read('date');
	$secret = "234lkjh234lkjh345lkjh345lKHWRE";
	$hash_input = $secret . $date;
	$gen_hash = hash("sha512", $hash_input);

	if ($gen_hash !== $hash) {
	    $ret = array();
	    $ret['error'] = "hash not valid";
	    $controller->response->body(json_encode($ret));
	    return false;
	    //throw new BadRequestException();
	}

	// check that the times are within 5 min - this is to prevent replay attack somehow
	$date_a = new DateTime($date);
	$date_b = new DateTime();
	$dif = date_diff($date_a, $date_b);
	$tot_seconds = $dif->d * 24 * 3600 + $dif->h * 3600 + $dif->i * 60 + $dif->s;

	if ($tot_seconds > 5*60) {
	    $ret = array();
	    $ret['error'] = "hash expired";
	    $controller->response->body(json_encode($ret));
	    return false;
	}

	return true;
    }

}