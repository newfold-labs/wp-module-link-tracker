<?php

namespace NewfoldLabs\WP\Module\LinkTracker;

/**
 * Module loading wpunit tests.
 *
 * @coversNothing
 */
class ModuleLoadingWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Verify WordPress factory is available.
	 *
	 * @return void
	 */
	public function test_wordpress_factory_available() {
		$this->assertTrue( function_exists( 'get_option' ) );
		$this->assertNotEmpty( get_option( 'blogname' ) );
	}

	/**
	 * Verify add_action exists (bootstrap uses it).
	 *
	 * @return void
	 */
	public function test_wordpress_hooks_available() {
		$this->assertTrue( function_exists( 'add_action' ) );
		$this->assertTrue( function_exists( 'add_filter' ) );
	}

	/**
	 * Verify LinkTracker class exists.
	 *
	 * @return void
	 */
	public function test_link_tracker_class_exists() {
		$this->assertTrue( class_exists( 'NewfoldLabs\WP\Module\LinkTracker\LinkTracker' ) );
	}

	/**
	 * Verify build_link function exists.
	 *
	 * @return void
	 */
	public function test_build_link_function_exists() {
		$this->assertTrue( function_exists( 'NewfoldLabs\WP\Module\LinkTracker\Functions\build_link' ) );
	}
}
