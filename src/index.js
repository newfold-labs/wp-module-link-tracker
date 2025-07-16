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
			const brand = window.NewfoldRuntime?.brand || 'bluehost';
			const utmSource =
				window.location.pathname +
				window.location.search +
				window.location.hash;

			const defaultParams = {
				channelid: url.includes( 'wp-admin' )
					? 'P99C100S1N0B3003A151D115E0000V111'
					: 'P99C100S1N0B3003A151D115E0000V112',
				utm_source: utmSource,
				utm_medium: brand + '_plugin',
			};

			const urlObj = new URL( url, window.location.origin );
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
