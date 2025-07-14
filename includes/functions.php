<?php

namespace NewfoldLabs\WP\Module\LinkTracker\Functions;

use NewfoldLabs\WP\Module\LinkTracker\LinkTracker;

use function NewfoldLabs\WP\ModuleLoader\container as getContainer;

/**
 * Builds a URL with query parameters.
 *
 * @param string $url The URL to which parameters will be appended.
 * @param array  $params An associative array of query parameters.
 * @return string The complete URL with query parameters.
 */
function build_link( string $url, array $params = array() ): string {
	// Check if source is passed in params.
	if ( ! empty( $params['source'] ) ) {
		$source = $params['source'];
		unset( $params['source'] );
	} else {
		$source = 'no_source';
	}

	$container = getContainer();

	$default_params = array(
		'channelid'    => strpos( $url, 'wp-admin' ) !== false ? 'P99C100S1N0B3003A151D115E0000V111' : 'P99C100S1N0B3003A151D115E0000V112',
		'utm_medium'   => $container ? $container->plugin()->get( 'id', 'bluehost' ) . '_plugin' : 'bluehost_plugin',
		'utm_campaign' => 'link_tracker',
		'utm_source'   => $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] . '?' . $source : $source,
	);

	$params  = array_merge( $default_params, $params );
	$tracker = new LinkTracker( $container );
	$url     = $tracker->build_link( $url, $params );

	return esc_url( $url );
}
