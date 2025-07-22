import { addQueryArgs } from '@wordpress/url';
/**
 * Newfold Link Tracker
 *
 * This section is responsible for tracking links and adding UTM parameters to URLs.
 * It attaches itself to the global NewfoldRuntime object.
 */
{
	const attachLinkTrackerToRuntime = () => {
		window.NewfoldRuntime.linkTracker = buildLinkTrackerObject();
	};

	const buildLinkTrackerObject = () => {
		return {
			addUtmParams,
		};
	};

	const addUtmParams = ( url, params = {} ) => {
		try {
			const excludeProtocols = [ 'tel:', 'mailto:' ];
			// Check if the URL starts with any exclude protocol
			if (
				excludeProtocols.some( ( protocol ) =>
					url.startsWith( protocol )
				)
			) {
				// If it does, return the URL unchanged
				return url;
			}

			const brand = window.NewfoldRuntime?.brand || 'bluehost';

			const utmSource =
				window.location.pathname +
				window.location.search +
				window.location.hash;

			// Check if the URL is relative or absolute
			if ( ! url.startsWith( 'http' ) && ! url.startsWith( '/' ) ) {
				const isInWpAdmin =
					window.location.pathname.includes( '/wp-admin/' );
				url = isInWpAdmin
					? window.location.origin + '/wp-admin/' + url
					: window.location.origin + '/' + url;
			}

			const urlObj = new URL( url );

			const defaultParams = {
				channelid: urlObj.pathname.includes( 'wp-admin' )
					? 'P99C100S1N0B3003A151D115E0000V111'
					: 'P99C100S1N0B3003A151D115E0000V112',
				utm_source: utmSource,
				utm_medium: brand + '_plugin',
			};

			const existingParams = {};
			urlObj.searchParams.forEach( ( value, key ) => {
				existingParams[ key ] = value;
			} );
			// Merge default parameters with the provided parameters
			const mergedParams = { ...defaultParams, ...params };
			Object.keys( existingParams ).forEach( ( key ) => {
				if ( mergedParams.hasOwnProperty( key ) ) {
					delete mergedParams[ key ];
				}
			} );

			return addQueryArgs( url, mergedParams );
		} catch ( e ) {
			return url;
		}
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		attachLinkTrackerToRuntime();
	} );
}
