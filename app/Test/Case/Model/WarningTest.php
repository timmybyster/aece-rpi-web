<?php
App::uses('Warning', 'Model');

/**
 * Warning Test Case
 *
 */
class WarningTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.warning',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Warning = ClassRegistry::init('Warning');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Warning);

		parent::tearDown();
	}

}
