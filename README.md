<a href="https://newfold.com/" target="_blank">
    <img src="https://newfold.com/content/experience-fragments/newfold/site-header/master/_jcr_content/root/header/logo.coreimg.svg/1621395071423/newfold-digital.svg" alt="Newfold Logo" title="Newfold Digital" align="right" 
height="42" />
</a>

# WordPress Link Tracker Module

Link Tracker functionality for WordPress.

## Module Responsibilities

- Includes PHP and JS functions that allow adding parameters to URLs.

## Releases

### 1. Bump Version

Update the module versions in the `bootstrap.php` file (the NFD_LINK_TRACKER_MODULE_VERSION const) and in the `package.json` file (the package version).

### 2. Build

Run `npm run build` to rebuild files and commit the new build files (be sure to remove the old version files).

## Installation

### 1. Add the Newfold Satis to your `composer.json`.

 ```bash
 composer config repositories.newfold composer https://newfold-labs.github.io/satis/
 ```

### 2. Require the `newfold-labs/wp-module-link-tracker` package.

 ```bash
 composer require newfold-labs/wp-module-link-tracker
 ```

### 3. How to use the module
The module offers the ability to use a PHP and a JS function to add parameters to the URL.

For PHP applications, you can use the `build_link` function in this way:

```php
use function NewfoldLabs\WP\Module\LinkTracker\Functions\build_link as buildLink;

$url = 'https://example.com';
$parameters = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => 'spring_sale',
);
$trackedUrl = buildLink($url, $parameters);
echo $trackedUrl; // Outputs: https://example.com/?utm_source=newsletter&utm_medium=email&utm_campaign=spring_sale   
```
If you don't provide any parameters, the function will return the original URL adding default parameters.
```php
use function NewfoldLabs\WP\Module\LinkTracker\Functions\build_link as buildLink;

$url = 'https://example.com';

$trackedUrl = buildLink($url);
echo $trackedUrl; // Outputs: https://example.com/?channelid=P99C100S1N0B3003A151D115E0000V112&utm_source=%2Fwp-admin%2Fadmin.php%3Fpage%3Dbluehost%23%2Fhome&utm_medium=bluehost_plugin   
```
For JavaScript applications, you can use the `NewfoldRuntime.linkTracker` window object.

The object exposes the `addUtmParams` function. You can use it like this:

```javascript

const url = 'https://example.com';

let trackerUrl = window.NewfoldRuntime.linkTracker.addUtmParams(url, {
        utm_source: 'newsletter',
        utm_medium: 'email',
        utm_campaign: 'spring_sale',
    });

console.log(trackedUrl); // Outputs: https://example.com/?utm_source=newsletter&utm_medium=email&utm_campaign=spring_sale
```

If you don't provide any parameters, the function will return the original URL adding default parameters.
```javascript

const url = 'https://example.com';


let trackerUrl = window.NewfoldRuntime.linkTracker.addUtmParams(url);
console.log(trackedUrl); // Outputs: https://example.com/?channelid=P99C100S1N0B3003A151D115E0000V112&utm_source=%2Fwp-admin%2Fadmin.php%3Fpage%3Dbluehost%23%2Fhome&utm_medium=bluehost_plugin
```


[More on Newfold WordPress Modules](https://github.com/newfold-labs/wp-module-loader)
