<?php

namespace NewfoldLabs\WP\Module\LinkTracker;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\ModuleLoader\Plugin;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test LinkTracker class.
 */
class LinkTrackerTest extends TestCase {

	/**
	 * The LinkTracker instance.
	 *
	 * @var LinkTracker
	 */
	protected $tracker;

	/**
	 * Set up the test environment.
	 */
	public function setUp(): void {
		WP_Mock::setUp();

		// Mock the Container and Plugin classes.
		$container = $this->createMock( Container::class );
		$plugin    = $this->createMock( Plugin::class );
		$plugin->method( 'get' )
			->willReturn( 'bluehost_plugin' );
		$container->method( 'plugin' )->willReturn(
			$plugin
		);
		$this->tracker = new LinkTracker( $container );

		// Mock the wp_parse_url function to return a parsed URL.
		// This is necessary because the build_url method uses wp_parse_url.
		WP_Mock::userFunction(
			'wp_parse_url',
			array(
				'return' => function ( $url ) {
					return parse_url( $url ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
				},
			)
		);
	}

	/**
	 * Tear down the test environment.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test that the external method correctly appends parameters to the URL.
	 */
	public function test_external_link_tracker() {

		$url   = 'https://example.com';
		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'https://example.com', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V112', $value );
		$this->assertStringContainsString( 'utm_source', $value );
	}

	/**
	 * Test that the internal link tracker correctly appends parameters to the URL.
	 */
	public function test_internal_link_tracker() {

		$url = 'https://example.com/wp-admin/admin.php';

		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V111', $value );
		$this->assertStringContainsString( 'utm_source', $value );
	}

	/**
	 * Test that the build_url method correctly appends additional parameters to the URL.
	 */
	public function test_build_url_with_params() {

		$url    = 'https://example.com';
		$params = array(
			'utm_test_param' => 'test_source',
			'custom_param'   => 'value',
		);

		$value = $this->tracker->build_url( $url, $params );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V112', $value );
		$this->assertStringContainsString( 'utm_test_param=test_source', $value );
		$this->assertStringContainsString( 'custom_param=value', $value );
	}

	/**
	 * Test that the build_url method correctly handles URLs with fragments.
	 */
	public function test_build_url_with_fragments() {

		$url = 'https://example.com#section1';

		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V112', $value );
		$this->assertStringContainsString( '#section1', $value );
	}

	/**
	 * Test that the build_url method correctly handles dynamic URLs.
	 */
	public function test_build_url_with_dynamic_url() {

		$url = 'wp-admin/admin.php?page=bluehost#/store/sales_discounts';

		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V111', $value );
		$this->assertStringContainsString( '#/store/sales_discounts', $value );
	}

	/**
	 * Test that the build_url method correctly handles URLs on a subdomain.
	 */
	public function test_build_url_on_subdomain() {

		$url = 'https://sub.example.com/wp-admin/admin.php?page=bluehost#/store/sales_discounts';

		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V111', $value );
		$this->assertStringContainsString( 'utm_source', $value );
		$this->assertStringContainsString( 'https://sub.example.com/', $value );
	}

	/**
	 * Test that the build_url method correctly handles URLs on a subdirectory.
	 */
	public function test_build_url_on_subdirectory() {

		$url = 'https://example.com/subdirectory/wp-admin/admin.php?page=bluehost#/store/sales_discounts';

		$value = $this->tracker->build_url( $url );

		$this->assertStringContainsString( 'utm_medium=bluehost_plugin', $value );
		$this->assertStringContainsString( 'channelid=P99C100S1N0B3003A151D115E0000V111', $value );
		$this->assertStringContainsString( 'utm_source', $value );
		$this->assertStringContainsString( 'https://example.com/subdirectory/', $value );
	}
}
