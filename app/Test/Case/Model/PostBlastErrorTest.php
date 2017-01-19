<?php
App::uses('PostBlastError', 'Model');

/**
 * PostBlastError Test Case
 *
 */
class PostBlastErrorTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post_blast_error'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PostBlastError = ClassRegistry::init('PostBlastError');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PostBlastError);

		parent::tearDown();
	}

/**
 * testPopulateNodeIds method
 *
 * @return void
 */
	public function testPopulateNodeIds() {
		$this->markTestIncomplete('testPopulateNodeIds not implemented.');
	}

}
