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
	}
	
	/**
	 * Builds a URL with query parameters.
	 *
	 * @param string $url The URL to which parameters will be appended.
	 * @param array $params An associative array of query parameters.
	 * @return string The complete URL with query parameters.
	 */
	public static function BuildLink(string $url, array $params = []): string
	{
		
		if ( ! empty( $params ) ) {
			$query     = '';
			$url_parts = parse_url($url);
			if (empty($url_parts['query'])) {
				$url_parts['query'] = '';
			} else {
				$query = $url_parts['query'];
			}
			
			$query .= '&' . http_build_query($params, '', '&');
			$query  = trim($query, '&');
			
			if ( empty( $url_parts['query'] ) ) {
				$url .= '?' . $query;
			} else {
				$url = str_replace( $url_parts['query'], $query, $url );
			}
		}
		return $url;
	}
}