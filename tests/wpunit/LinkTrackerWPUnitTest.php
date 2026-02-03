<?php

namespace NewfoldLabs\WP\Module\LinkTracker;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Tests for LinkTracker class.
 *
 * @covers \NewfoldLabs\WP\Module\LinkTracker\LinkTracker
 */
class LinkTrackerWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Create a minimal container mock for build_url.
	 *
	 * @param string $plugin_id Plugin id for utm_medium (e.g. 'bluehost').
	 * @return Container
	 */
	private function create_container_mock( $plugin_id = 'bluehost' ) {
		// phpcs:disable Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.FunctionComment.Missing,Universal.NamingConventions.NoReservedKeywordParameterNames -- plugin mock for tests
		$plugin    = new class( $plugin_id ) {
			private $id;
			public function __construct( $id ) {
				$this->id = $id;
			}
			public function get( $key, $fallback = null ) {
				return 'id' === $key ? $this->id : $fallback;
			}
		};
		// phpcs:enable Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.FunctionComment.Missing,Universal.NamingConventions.NoReservedKeywordParameterNames
		$container = $this->createMock( Container::class );
		$container->method( 'plugin' )->willReturn( $plugin );
		return $container;
	}

	/**
	 * Build_url adds default query params to a URL.
	 *
	 * @return void
	 */
	public function test_build_url_adds_default_params() {
		$container = $this->create_container_mock( 'bluehost' );
		$tracker   = new LinkTracker( $container );
		$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
		$url       = $tracker->build_url( 'https://example.com/page', array() );
		$this->assertStringContainsString( 'channelid=', $url );
		$this->assertStringContainsString( 'utm_medium=', $url );
		$this->assertStringContainsString( 'utm_source=', $url );
		$this->assertStringContainsString( 'example.com', $url );
		parse_str( (string) wp_parse_url( $url, PHP_URL_QUERY ), $params );
		$this->assertArrayHasKey( 'utm_medium', $params );
		$this->assertSame( 'bluehost_plugin', $params['utm_medium'] );
	}

	/**
	 * Build_url merges provided params with defaults.
	 *
	 * @return void
	 */
	public function test_build_url_merges_provided_params() {
		$container = $this->create_container_mock( 'bluehost' );
		$tracker   = new LinkTracker( $container );
		$_SERVER['PHP_SELF'] = '/wp-admin/options.php';
		$url       = $tracker->build_url( 'https://example.com/', array( 'custom' => 'value' ) );
		parse_str( (string) wp_parse_url( $url, PHP_URL_QUERY ), $params );
		$this->assertSame( 'value', $params['custom'] );
	}

	/**
	 * Build_url uses different channelid for wp-admin vs front.
	 *
	 * @return void
	 */
	public function test_build_url_channelid_wp_admin_vs_front() {
		$container  = $this->create_container_mock( 'bluehost' );
		$tracker    = new LinkTracker( $container );
		$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
		$admin_url  = $tracker->build_url( 'https://example.com/wp-admin/', array() );
		$front_url  = $tracker->build_url( 'https://example.com/', array() );
		parse_str( (string) wp_parse_url( $admin_url, PHP_URL_QUERY ), $admin_params );
		parse_str( (string) wp_parse_url( $front_url, PHP_URL_QUERY ), $front_params );
		$this->assertNotSame( $admin_params['channelid'] ?? '', $front_params['channelid'] ?? '' );
	}

	/**
	 * Add_hooks registers admin_enqueue_scripts and nfd_build_url filter.
	 *
	 * @return void
	 */
	public function test_add_hooks_registers_filter() {
		$container = $this->create_container_mock();
		$tracker   = new LinkTracker( $container );
		$tracker->add_hooks();
		$this->assertNotFalse( has_filter( 'nfd_build_url', array( $tracker, 'build_url' ) ) );
	}
}
