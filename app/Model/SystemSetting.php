<?php

App::uses('AppModel', 'Model');
/**
 * SystemSetting Model
 *
 */
define('LOG_TIME_TO_KEEP', 'log_time_to_keep');
define('WARNING_DISMISS_DELAY', 'warning_dismiss_delay');
define('BACKGROUND_IMG_SIZE_MULTIPLY', 'background_image_size_multiply');
define('BACKGROUND_IMG_CONTRAST', 'background_image_contrast');
define('NETWORK_SSID', 'network_ssid');
define('NETWORK_ENCRYPTION_TYPE', 'network_encryption_type');
define('NETWORK_PASSWORD', 'network_password');
define('NETWORK_USE_DHCP', 'network_use_dhcp');
define('NETWORK_IP', 'network_ip');
define('NETWORK_SUBNET_MASK', 'network_subnet_mask');
define('NETWORK_DEFAULT_GATEWAY', 'network_default_gateway');

class SystemSetting extends AppModel {

    public $displayField = "name";

    public static function logTimeToKeepPeriods($value = null) {
	$options = array(
	    self::LOG_TIMETOKEEP_PERIOD_FOREVER => __('forever', true),
	    self::LOG_TIMETOKEEP_PERIOD_7_DAYS => __('7 days', true),
	    self::LOG_TIMETOKEEP_PERIOD_30_DAYS => __('30 days', true),
	    self::LOG_TIMETOKEEP_PERIOD_90_DAYS => __('90 days', true)
	);
	return parent::enum($value, $options);
    }

    const LOG_TIMETOKEEP_PERIOD_FOREVER = 1;
    const LOG_TIMETOKEEP_PERIOD_7_DAYS = 2;
    const LOG_TIMETOKEEP_PERIOD_30_DAYS = 3;
    const LOG_TIMETOKEEP_PERIOD_90_DAYS = 4;
    
    public static function networkEncryptionEnums($value = null) {
	$options = array(
	    self::ENCRYPTION_WPA2_AES => __('WPA2 + AES', true),
	    self::ENCRYPTION_WPA_AES => __('WPA + AES', true),
	    self::ENCRYPTION_WPA_TKIPORAES => __('WPA + TKIP/AES', true),
	    self::ENCRYPTION_WPA_TKIP => __('WPA + TKIP', true),
	    self::ENCRYPTION_WEP => __('WEP', true),
	    self::ENCRYPTION_NONE => __('None', true),
	);
	return parent::enum($value, $options);
    }
    
    const ENCRYPTION_WPA2_AES = 1;
    const ENCRYPTION_WPA_AES = 2;
    const ENCRYPTION_WPA_TKIPORAES = 3;
    const ENCRYPTION_WPA_TKIP = 4;
    const ENCRYPTION_WEP = 5;
    const ENCRYPTION_NONE = 6;

    // network settings
    public function getNetworkDefaultGateway(){
	return $this->getSetting(NETWORK_DEFAULT_GATEWAY, '192.168.1.255');
    }

    public function setNetworkDefaultGateway($gateway) {
	$this->setSetting(NETWORK_DEFAULT_GATEWAY, $gateway);
    }
    
    public function getNetworkSubnetMask(){
	return $this->getSetting(NETWORK_SUBNET_MASK, '255.255.255.0');
    }

    public function setNetworkSubnetMask($mask) {
	$this->setSetting(NETWORK_SUBNET_MASK, $mask);
    }
    
    public function getNetworkIP(){
	return $this->getSetting(NETWORK_IP, '192.168.1.1');
    }

    public function setNetworkIP($ip) {
	$this->setSetting(NETWORK_IP, $ip);
    }
    
    public function getNetworkUseDHCP(){
	return $this->getSetting(NETWORK_USE_DHCP, 0);
    }

    public function setNetworkUseDHCP($use_dhcp) {
	$this->setSetting(NETWORK_USE_DHCP, $use_dhcp);
    }
    
    public function getNetworkPassword(){
	return $this->getSetting(NETWORK_PASSWORD, '');
    }

    public function setNetworkPassword($passw) {
	$this->setSetting(NETWORK_PASSWORD, $passw);
    }
    
    public function getNetworkEncryptionType(){
	return $this->getSetting(NETWORK_ENCRYPTION_TYPE, 6);
    }

    public function setNetworkEncryptionType($enc_type) {
	$this->setSetting(NETWORK_ENCRYPTION_TYPE, $enc_type);
    }
    
    public function getNetworkSSID(){
	return $this->getSetting(NETWORK_SSID, '');
    }

    public function setNetworkSSID($ssid) {
	$this->setSetting(NETWORK_SSID, $ssid);
    }
    
    // set period at which log will be removed
    public function getLogPeriod() {
	return $this->getSetting(LOG_TIME_TO_KEEP, 4);
    }

    // set the time to the current time
    public function setLogPeriod($period) {
	$this->setSetting(LOG_TIME_TO_KEEP, $period);
    }
    
    public function getWarningDismissDelay() {
	return $this->getSetting(WARNING_DISMISS_DELAY, 30);	
    }
        
    public function setWarningDismissDelay($delay) {
	$this->setSetting(WARNING_DISMISS_DELAY, $delay);	
    }
    
    public function setBackgroundImageSizeMultiply($factor){
	$this->setSetting(BACKGROUND_IMG_SIZE_MULTIPLY, $factor);
    }
    
    public function getBackgroundImageSizeMultiply(){
	return $this->getSetting(BACKGROUND_IMG_SIZE_MULTIPLY, 2);
    }
    
    public function setBackgroundImageContrast($contrast){
	$this->setSetting(BACKGROUND_IMG_CONTRAST, $contrast);
    }
    
    public function getBackgroundImageContrast(){
	return $this->getSetting(BACKGROUND_IMG_CONTRAST, 51);
    }   

    // Checks for a setting, if it doesn't exist create it
    public function getSetting($name, $default_val = 0) {
	$var = $this->findByName($name);
	if ($var)
	    return $var['SystemSetting']['value'];
	else {
	    $data = array();
	    $data['SystemSetting']['name'] = $name;
	    $data['SystemSetting']['value'] = $default_val;
	    $this->save($data);
	    return $default_val;
	}
    }

    public function setSetting($name, $new_val) {
	$data = array();
	//by doing a find we make sure that we are using the previous id if there is one not creating a new entry in the db
	$data = $this->findByName($name);
	if ($data == null)
	    $data = array();
	$data['SystemSetting']['name'] = $name;
	$data['SystemSetting']['value'] = $new_val;
	$this->save($data);
    }

}
