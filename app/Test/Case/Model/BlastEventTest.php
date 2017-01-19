<?php
App::uses('BlastEvent', 'Model');

/**
 * BlastEvent Test Case
 *
 */
class BlastEventTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.blast_event',
		'app.post_blast_error'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BlastEvent = ClassRegistry::init('BlastEvent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BlastEvent);

		parent::tearDown();
	}

}
