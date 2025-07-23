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

	// Function to add query parameters to a URL while preserving the fragment (hash) part.
	function addQueryArgsPreserveFragment( url, params ) {
		const [ baseUrl, fragment ] = url.split( '#', 2 );
		const urlObj = new URL( baseUrl, window.location.origin );
		Object.entries( params ).forEach( ( [ key, value ] ) => {
			urlObj.searchParams.set( key, value );
		} );
		return urlObj.toString() + ( fragment ? '#' + fragment : '' );
	}

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

			const urlObj = new URL( url, window.location.origin );

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

			const mergedParams = { ...defaultParams, ...params };
			Object.keys( existingParams ).forEach( ( key ) => {
				if ( mergedParams.hasOwnProperty( key ) ) {
					delete mergedParams[ key ];
				}
			} );

			return addQueryArgsPreserveFragment( url, mergedParams );
		} catch ( e ) {
			return url;
		}
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		attachLinkTrackerToRuntime();
	} );
}
