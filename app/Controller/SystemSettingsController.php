<?php
App::uses('AppController', 'Controller');
/**
 <?php
App::uses('AppController', 'Controller');
/**
 * SystemSettings Controller
 *
 */
class SystemSettingsController extends AppController {


    public function config_time(){

        // check for windows or linux environment
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $pyscript = APP.'\\pi_scripts\\test123.py';
            $python = 'C:\\Python27\\python.exe';
            $cmd = "$python $pyscript";
        } else { // linux
            $pyscript = APP.'/pi_scripts/test123.py';
            $cmd = "$pyscript";
        }

        $output = "hello";
        exec("$cmd", $output);
        debug('');
        debug($output);
        debug($pyscript);
        debug('fff');

        App::uses('CakeTime', 'Utility');
        $time = CakeTime::i18nFormat(time());
        $time = CakeTime::format(time(), '%Y-%m-%d %H:%M:%S');

        if ($this->request->is('post')) {
            $recvtime = $this->request->data['SystemSettings']['time'];
            // run the python script to apply the time settings
            //$command = escapeshellcmd("sudo python /var/www/AEC/app/pi_scripts/test123.py '$recvtime'");
            //$output = passthru($command);
            $recvtime = escapeshellarg($recvtime);
            $command = escapeshellcmd("python /var/www/AEC/app/pi_scripts/test123.py $recvtime");

	    $output = passthru($command);
	    debug('');
	    debug($output);
	    $this->setFlashBootstrap(__('Time saved'), 'alert-success');
	    $time = $this->request->data['SystemSettings']['time'];
	}
	
	$this->set(compact('time'));
    }
    
    public function config_network(){
	if ($this->request->is('post')) {
	    $this->SystemSetting->setNetworkSSID($this->request->data['SystemSetting']['SSID']);	
	    $this->SystemSetting->setNetworkEncryptionType($this->request->data['SystemSetting']['encryption_type']);
	    $this->SystemSetting->setNetworkPassword($this->request->data['SystemSetting']['network_password']);
	    $this->SystemSetting->setNetworkUseDHCP($this->request->data['SystemSetting']['use_dhcp']);
	    $this->SystemSetting->setNetworkIP($this->request->data['SystemSetting']['ip']);
	    $this->SystemSetting->setNetworkSubnetMask($this->request->data['SystemSetting']['subnet_mask']);
	    $this->SystemSetting->setNetworkDefaultGateway($this->request->data['SystemSetting']['default_gateway']);
	    $this->Session->setFlash(__('Settings updated'), 'default', array('class' => 'alert alert-success'));
	    $network_encryption_enum = $this->SystemSetting->networkEncryptionEnums();
	    $this->set(compact('network_encryption_enum'));
	    return;
	}
	$data['SystemSetting']['SSID'] = $this->SystemSetting->getNetworkSSID();
	$data['SystemSetting']['encryption_type'] = $this->SystemSetting->getNetworkEncryptionType();
	$data['SystemSetting']['network_password'] = $this->SystemSetting->getNetworkPassword();
	$data['SystemSetting']['use_dhcp'] = $this->SystemSetting->getNetworkUseDHCP();
	
	$data['SystemSetting']['ip'] = $this->SystemSetting->getNetworkIP();
	$data['SystemSetting']['subnet_mask'] = $this->SystemSetting->getNetworkSubnetMask();
	$data['SystemSetting']['default_gateway'] = $this->SystemSetting->getNetworkDefaultGateway();
	
	$network_encryption_enum = $this->SystemSetting->networkEncryptionEnums();
	$this->request->data = $data;
	$this->set(compact('network_encryption_enum'));
    }
}
