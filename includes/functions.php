<?php

namespace NewfoldLabs\WP\Module\LinkTracker\Functions;

use function NewfoldLabs\WP\ModuleLoader\container as getContainer;

/**
 * Builds a URL with query parameters.
 *
 * @param string $url The URL to which parameters will be appended.
 * @param array  $params An associative array of query parameters.
 * @return string The complete URL with query parameters.
 */
function build_link( string $url, $params = array() ) {
	
	$container = getContainer();

	$source = false;
	if ( ! empty( $params['source'] ) ) {
		$source = $params['source'];
		unset( $params['source'] );
	}

	$parts = wp_parse_url( $url );
	
	
	$query_params = array();
	if ( isset( $parts['query'] ) ) {
		parse_str( $parts['query'], $query_params );
	}

	$default_params = array(
		'channelid'  => strpos( $url, 'wp-admin' ) !== false ? 'P99C100S1N0B3003A151D115E0000V111' : 'P99C100S1N0B3003A151D115E0000V112',
		'utm_medium' => $container ? $container->plugin()->get( 'id', 'bluehost' ) . '_plugin' : 'bluehost_plugin',
		'utm_source' => ( $_SERVER['PHP_SELF'] ?? '' ) . ( $source ? '?' . $source : false ),
	);

	foreach ( $default_params as $key => $value ) {
		if ( ! array_key_exists( $key, $query_params ) ) {
			$query_params[ $key ] = $value;
		}
	}
	// Merge the default parameters with the provided parameters and clean the empty parameters.
	$query_params = array_filter(
		! empty( $params ) ? array_merge( $params, $query_params ) : $query_params,
		function ( $value ) {
			return null !== $value && '' !== $value && false !== $value;
		}
	);
	// Build the final URL with the query parameters.
	$base = ( isset( $parts['scheme'] ) ? $parts['scheme'] . '://' : '' ) .
		( isset( $parts['host'] ) ? $parts['host'] : '' ) .
		( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) .
		( isset( $parts['path'] ) ? $parts['path'] : '' );

	$final_url = $base . '?' . http_build_query( $query_params, '', '&' );

	return esc_url( $final_url );
}
