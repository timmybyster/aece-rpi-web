<?php
/**
 * NodeFixture
 *
 */
class NodeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'x' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'Position on the system status page'),
		'y' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'Position on the system status page'),
		'serial' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'key_switch_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'communication_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'temperature' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'blast_armed' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'fire_button' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'isolation_relay' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'cable_fault' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'earth_leakage' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'detonator_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'partial_blast_lfs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'full_blast_lfs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'booster_fired_lfs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'missing_pulse_detected_lfs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'AC_supply_voltage_lfs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'DC_supply_voltage' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'DC_supply_voltage_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'mains' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'x' => 1,
			'y' => 1,
			'serial' => 'Lorem ipsum dolor sit amet',
			'type' => 1,
			'key_switch_status' => 1,
			'communication_status' => 1,
			'temperature' => 1,
			'blast_armed' => 1,
			'fire_button' => 1,
			'isolation_relay' => 1,
			'cable_fault' => 1,
			'earth_leakage' => 1,
			'detonator_status' => 1,
			'partial_blast_lfs' => 1,
			'full_blast_lfs' => 1,
			'booster_fired_lfs' => 1,
			'missing_pulse_detected_lfs' => 1,
			'AC_supply_voltage_lfs' => 1,
			'DC_supply_voltage' => 1,
			'DC_supply_voltage_status' => 1,
			'mains' => 1,
			'created' => '2015-01-26 10:57:03',
			'modified' => '2015-01-26 10:57:03'
		),
	);

}
