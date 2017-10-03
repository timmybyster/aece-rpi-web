<?php

App::uses('AppModel', 'Model');

/**
 * Node Model
 *
 */
class Node extends AppModel {
    
    public static function types($value = null) {
	$options = array(
	    self::TYPE_BC1  => __('CCB', true),
	    self::TYPE_ISC1  => __('ISC-1', true),	 
	    self::TYPE_IB651  => __('IB651', true),
		self::TYPE_CBB  => __('CBB', true),
		self::TYPE_EDD  => __('EDD', true)
	);
	return parent::enum($value, $options);
    }        
    const TYPE_BC1 = 0;
    const TYPE_ISC1 = 1;
    const TYPE_IB651 = 2;
	const TYPE_CBB = 3;
	const TYPE_EDD = 4;
    
    public static function communication_status_enum($value = null) {
	$options = array(
	    self::COMMUNICATION_STATUS_OFF  => __('OFF', true),	
	    self::COMMUNICATION_STATUS_ON  => __('ON', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const COMMUNICATION_STATUS_OFF = 0;
    const COMMUNICATION_STATUS_ON = 1;
	
	public static function fire_button_enum($value = null) {
	$options = array(
	    self::FIRE_BUTTON_OFF  => __('NOT PRESSED', true),	
	    self::FIRE_BUTTON_ON  => __('PRESSED', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const FIRE_BUTTON_OFF = 0;
    const FIRE_BUTTON_ON = 1;
	
	public static function isolation_status_enum($value = null) {
	$options = array(
	    self::ISOLATION_STATUS_OFF  => __('OPEN', true),	
	    self::ISOLATION_STATUS_ON  => __('CLOSED', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const ISOLATION_STATUS_OFF = 0;
    const ISOLATION_STATUS_ON = 1;
	
	public static function cable_fault_enum($value = null) {
	$options = array(
	    self::CABLE_FAULT_OFF  => __('No Fault', true),	
	    self::CABLE_FAULT_ON  => __('Fault Detected', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const CABLE_FAULT_OFF = 0;
    const CABLE_FAULT_ON = 1;
	
	public static function blast_armed_enum($value = null) {
	$options = array(
	    self::BLAST_DISARMED  => __('DISARMED', true),	
	    self::BLAST_ARMED  => __('ARMED', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const BLAST_DISARMED = 0;
    const BLAST_ARMED = 1;
	
	public static function earth_leakage_enum($value = null) {
	$options = array(
	    self::EARTH_LEAKAGE_OFF  => __('No Fault', true),	
	    self::EARTH_LEAKAGE_ON  => __('Fault Detected', true),	     	    
	);
	return parent::enum($value, $options);
    }        
    const EARTH_LEAKAGE_OFF = 0;
    const EARTH_LEAKAGE_ON = 1;
        
    public static function key_switch_enum($value = null) {
	$options = array(
	    self::KEY_SWITCH_DISARMED  => __('DISARMED', true),	
	    self::KEY_SWITCH_ARMED  => __('ARMED', true),	     	    
	);
	return parent::enum($value, $options);
    }   
    const KEY_SWITCH_DISARMED = 0;
    const KEY_SWITCH_ARMED = 1;
	
	public static function isc_key_switch_enum($value = null) {
	$options = array(
	    self::ISC_KEY_SWITCH_DISARMED  => __('ON', true),	
	    self::ISC_KEY_SWITCH_ARMED  => __('ISOLATED', true),	     	    
	);
	return parent::enum($value, $options);
    }   
    const ISC_KEY_SWITCH_DISARMED = 0;	
    const ISC_KEY_SWITCH_ARMED = 1;
            
    public static function detonator_status_enum($value = null) {
	$options = array(
	    self::DETONATOR_STATUS_NOT_CONNECTED  => __('NOT CONNECTED', true),	     	    
	    self::DETONATOR_STATUS_CONNECTED  => __('CONNECTED', true),		    
	);
	
	return parent::enum($value, $options);
    }   
    const DETONATOR_STATUS_NOT_CONNECTED = 0;
    const DETONATOR_STATUS_CONNECTED = 1;
     	
    public static function full_blast_lfs_enum($value = null) {
	$options = array(
	    self::FULL_BLAST_NOT_PRESENT  => __('NOT PRESENT', true),		    
	    self::FULL_BLAST_PRESENT  => __('PRESENT', true)	    
	);
	return parent::enum($value, $options);
    }   
    const FULL_BLAST_NOT_PRESENT = 0;
    const FULL_BLAST_PRESENT = 1;
    
    public static function partial_blast_lfs_enum($value = null) {
	$options = array(
	    self::PARTIAL_BLAST_NOT_PRESENT  => __('No Error', true),		    
	    self::PARTIAL_BLAST_PRESENT  => __('Detonator Error', true)	    
	);
	return parent::enum($value, $options);
    }   
    const PARTIAL_BLAST_NOT_PRESENT = 0;
    const PARTIAL_BLAST_PRESENT = 1;
    
    public static function booster_fired_lfs_enum($value = null) {
	$options = array(
	    self::BOOSTER_FIRED_NOT  => __('NOT FIRED', true),
	    self::BOOSTER_FIRED  => __('FIRED', true)	    	    	    
	);
	return parent::enum($value, $options);
    }   
    const BOOSTER_FIRED_NOT = 0;
    const BOOSTER_FIRED = 1;
    
    public static function missing_pulse_detected_lfs_enum($value = null) {
	$options = array(	    
	    self::MISSING_PULSE_DETECTED_NO  => __('NO', true),   	    	    
	    self::MISSING_PULSE_DETECTED_YES  => __('YES', true)
	);
	return parent::enum($value, $options);
    }   
    const MISSING_PULSE_DETECTED_NO = 0;
    const MISSING_PULSE_DETECTED_YES = 1;
	
	public static function mains_enum($value = null) {
	$options = array(	    
	    self::MAINS_NO  => __('NOT PRESENT', true),   	    	    
	    self::MAINS_YES  => __('PRESENT', true)
	);
	return parent::enum($value, $options);
    }   
    const MAINS_NO = 0;
    const MAINS_YES = 1;
	
	public static function tagged_enum($value = null) {
	$options = array(	    
	    self::TAGGED_NO  => __('NOT TAGGED', true),   	    	    
	    self::TAGGED_YES  => __('TAGGED', true)
	);
	return parent::enum($value, $options);
    }   
    const TAGGED_NO = 0;
    const TAGGED_YES = 1;
	
	public static function logged_enum($value = null) {
	$options = array(	    
	    self::LOGGED_NO  => __('NOT BLUETOOTH', true),   	    	    
	    self::TAGGED_YES  => __('BLUETOOTH', true)
	);
	return parent::enum($value, $options);
    }   
    const LOGGED_NO = 0;
    const LOGGED_YES = 1;
	
	public static function calibrated_enum($value = null) {
	$options = array(	    
	    self::CALIBRATED_NO  => __('NOT CALIBRATED', true),   	    	    
	    self::CALIBRATED_YES  => __('CALIBRATED', true)
	);
	return parent::enum($value, $options);
    }   
    const CALIBRATED_NO = 0;
    const CALIBRATED_YES = 1;
	
	public static function DC_supply_voltage_status_enum($value = null) {
	$options = array(	    
	    self::VOLTAGE_NO  => __('NOT PRESENT', true),   	    	    
	    self::VOLTAGE_YES  => __('PRESENT', true)
	);
	return parent::enum($value, $options);
    }   
    const VOLTAGE_NO = 0;
    const VOLTAGE_YES = 1;
    
    public $displayField = "serial";
	//public $displayField = "delay";
	
    public $belongsTo = array(
	'Parent' => array(
	    'className' => 'Node',
	    'foreignKey' => 'parent_id'
	)
    );
    
    public function populateEnums($data){
	$newdata = array();
	foreach ($data as $node){
	    $node['Node']['type'] = $this->types()[$node['Node']['type_id']];
	    if ($node['Node']['communication_status'] != null)
		$node['Node']['communication_status_text'] = $this->communication_status_enum()[$node['Node']['communication_status']];
		if ($node['Node']['cable_fault'] != null)
		$node['Node']['cable_fault_text'] = $this->cable_fault_enum()[$node['Node']['cable_fault']];
		if ($node['Node']['earth_leakage'] != null)
		$node['Node']['earth_leakage_text'] = $this->earth_leakage_enum()[$node['Node']['earth_leakage']];
		if ($node['Node']['fire_button'] != null)
		$node['Node']['fire_button_text'] = $this->fire_button_enum()[$node['Node']['fire_button']];
	    if ($node['Node']['isolation_relay'] != null)
		$node['Node']['isolation_status_text'] = $this->isolation_status_enum()[$node['Node']['isolation_relay']];
	    if ($node['Node']['blast_armed'] != null)
		$node['Node']['blast_armed_text'] = $this->blast_armed_enum()[$node['Node']['blast_armed']];
	    if ($node['Node']['key_switch_status'] != null){
		$node['Node']['key_switch_status_text'] = $this->key_switch_enum()[$node['Node']['key_switch_status']];
		$node['Node']['isc_key_switch_status_text'] = $this->isc_key_switch_enum()[$node['Node']['key_switch_status']];}
	    if ($node['Node']['detonator_status'] != null)
		$node['Node']['detonator_status_text'] = $this->detonator_status_enum()[$node['Node']['detonator_status']];
	    if ($node['Node']['full_blast_lfs'] != null)
		$node['Node']['full_blast_lfs_text'] = $this->full_blast_lfs_enum()[$node['Node']['full_blast_lfs']];
	    if ($node['Node']['partial_blast_lfs'] != null)
		$node['Node']['partial_blast_lfs_text'] = $this->partial_blast_lfs_enum()[$node['Node']['partial_blast_lfs']];
	    if ($node['Node']['booster_fired_lfs'] != null)
		$node['Node']['booster_fired_lfs_text'] = $this->booster_fired_lfs_enum()[$node['Node']['booster_fired_lfs']];
	    if ($node['Node']['missing_pulse_detected_lfs'] != null)
		$node['Node']['missing_pulse_detected_lfs_text'] = $this->missing_pulse_detected_lfs_enum()[$node['Node']['missing_pulse_detected_lfs']];
		if ($node['Node']['mains'] != null)
		$node['Node']['mains_text'] = $this->mains_enum()[$node['Node']['mains']];
		if ($node['Node']['DC_supply_voltage_status'] != null)
		$node['Node']['DC_supply_voltage_status_text'] = $this->mains_enum()[$node['Node']['DC_supply_voltage_status']];
		if ($node['Node']['tagged'] != null)
		$node['Node']['tagged_text'] = $this->tagged_enum()[$node['Node']['tagged']];
		if ($node['Node']['logged'] != null)
		$node['Node']['logged_text'] = $this->logged_enum()[$node['Node']['logged']];
		if ($node['Node']['calibration'] != null)
		$node['Node']['calibration_text'] = $this->calibrated_enum()[$node['Node']['calibration']];
	    array_push($newdata, $node);
	}
	return $newdata;
    }

}
