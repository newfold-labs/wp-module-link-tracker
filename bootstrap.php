<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\LinkTracker\LinkTracker;
use function NewfoldLabs\WP\ModuleLoader\register;

// Do not allow multiple copies of the module to be activated.
if ( defined( 'NFD_LINK_TRACKER_MODULE_VERSION' ) ) {
	return;
}

define( 'NFD_LINK_TRACKER_MODULE_VERSION', '1.0.0' );


if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'wp-module-link-tracker',
					'label'    => 'Link Tracker',
					'callback' => function ( Container $container ) {
						if ( ! defined( 'NFD_LINK_TRACKER_BUILD_URL' ) ) {
							define( 'NFD_LINK_TRACKER_BUILD_URL', $container->plugin()->url . 'vendor/newfold-labs/wp-module-link-tracker/build' );
						}
						if ( ! defined( 'NFD_LINK_TRACKER_BUILD_DIR' ) ) {
							define( 'NFD_LINK_TRACKER_BUILD_DIR', $container->plugin()->dir . 'vendor/newfold-labs/wp-module-link-tracker/build' );
						}
						
						return new LinkTracker( $container );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
