<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'wp-module-link-tracker',
					'label'    => 'Link Tracker',
					'callback' => function ( Container $container ) {
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
