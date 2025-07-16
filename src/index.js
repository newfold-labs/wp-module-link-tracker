import { addQueryArgs } from "@wordpress/url";
/**
 * Newfold Link Tracker
 *
 * This module is responsible for tracking links and adding UTM parameters to URLs.
 * It attaches itself to the global NewfoldRuntime object.
 *
 * @module NewfoldLinkTracker
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

    const addUtmParams =  ( url, params = {} ) => {
        if ( ! url ) {
            return url;
        }
        if ( ! params ) {
            params = {};
        }

        let brand = window.NewfoldRuntime?.brand || 'bluehost';
        let utmSource = window.location.pathname + window.location.search + window.location.hash;

        // Add UTM parameters to the URL
        params.channelid = params.channelid ? params.channelid: ( url.includes('wp-admin') ? 'P99C100S1N0B3003A151D115E0000V111' : 'P99C100S1N0B3003A151D115E0000V112');
        params.utm_source = params.utm_source ? params.utm_source : utmSource;
        params.utm_medium = params.utm_medium ? params.utm_medium : brand+'_plugin';

        return addQueryArgs(url, params);
    }

    window.addEventListener( 'DOMContentLoaded', () => {
        attachLinkTrackerToRuntime();
    } );
}