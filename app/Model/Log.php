<?php
App::uses('AppModel', 'Model');
/**
 * Log Model
 *
 */
class Log extends AppModel {

    
    // after every save, do a check to see if we we should delete logs, as configured in the SystemSettings
    public function afterSave($created, $options = array()){

	$SystemSetting = ClassRegistry::init('SystemSetting');
	$period_id = $SystemSetting->getLogPeriod();
	$period = $SystemSetting->logTimeToKeepPeriods()[$period_id];
	if ($period == "forever")
	    return;
	if ($period == "7 days")
	    $this->deleteAll(array('created <' => date('Y-m-d H:i:s', strtotime('-7 day'))), false);
	if ($period == "30 days")
	    $this->deleteAll(array('created <' => date('Y-m-d H:i:s', strtotime('-30 day'))), false);
	if ($period == "90 days")
	    $this->deleteAll(array('created <' => date('Y-m-d H:i:s', strtotime('-90 day'))), false);
    }
    
    
}
