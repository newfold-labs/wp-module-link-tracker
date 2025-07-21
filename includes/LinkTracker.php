<?php

namespace NewfoldLabs\WP\Module\LinkTracker;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Manages all the functionalities for the module.
 */
class LinkTracker {
	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor for the LinkTracker class.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( Container $container ) {

		$this->container = $container;
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
	}

	/**
	 * Enqueues the JavaScript file for the link tracker.
	 *
	 * This method registers and enqueues the JavaScript file that will handle
	 * the link tracking functionality on the front end.
	 */
	public function enqueue_scripts() {
		
		$asset_file = NFD_LINK_TRACKER_BUILD_DIR . '/index.asset.php';
		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;
		} else {
			return;
		}
		
		wp_register_script(
			'wp-module-link-tracker',
			NFD_LINK_TRACKER_BUILD_URL . '/index.js',
			array_merge( $asset['dependencies'] ,array( 'nfd-runtime' ) ),
			$asset['version'],
			true
		);

		wp_enqueue_script( 'wp-module-link-tracker' );
	}
}
