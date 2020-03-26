<p align="center"><a target="_blank" href="https://lsx.lsdev.biz/"><img width="100px;" src="https://lsx.lsdev.biz/wp-content/uploads/2019/02/geolocate_550_x550_480.png" alt="LSX Geo Content Extension"></a>
</p>
<h1 align="center">LSX Geo Content</h1>

<p align="center">
	  <a href="https://www.gnu.org/licenses/gpl-3.0.en.html"><img src="https://poser.pugx.org/woocommerce/woocommerce/license" alt="license"></a>
    <a href="http://gulpjs.com/"><img src="https://img.shields.io/badge/built%20with-gulp.js-green.svg" alt="Built with gulp.js"></a>
  	<a href="https://travis-ci.org/github/lightspeeddevelopment/lsx-geo-content"><img src="https://travis-ci.org/lightspeeddevelopment/lsx-geo-content.svg?branch=master" alt="Build Status"></a>
    <a href="https://lsx.lsdev.biz/"><img src="https://lsx.lsdev.biz/wp-content/uploads/2019/06/Designed-for-LSX-Theme-blue.png" alt="Made for LSX"></a>
</p>

Welcome to the [LSX Geo Content](https://lsx.lsdev.biz/documentation/lsx-geo-content/). The LSX Geo Content plugin detects your IP address and the Protocol Version (v4 or v6) used. Your country code is then determined using the downloaded DB from https://dev.maxmind.com. This is cached for 1 hour.

It is compatible with Caldera Forms plugin, as it allows to pre-populate content based on the location.

## Description
The LSX Geo Content plugin detects your IP address and the Protocol Version (v4 or v6) used. Your country code is then determined using the downloaded DB from https://dev.maxmind.com. This is cached for 1 hour.

It is compatible with Caldera Forms plugin, as it allows to pre-populate content based on the location.

## Shortcode
You can use any of the following inside a WordPress editor, or a shortcode enabled field.

``[geo_content countr"ZA"] You are in South Africa [/geo_content]

``[geo_content countr"US"] You are in the US [/geo_content]

## Template Tags
Replace country code with the 2 digit country code.
-  United States ## US
-  United Kingdom ## GB
- South Africa ## ZA

``<?php
 ``if ( lsx_geo_is_country( $country_code ) ) {
    ``// Do code
    ``} ?>

## Caldera Forms
Add in one of the following custom CSS classes to the field you wish to pre-populate. The filters only work with the "text" and "dropdown" type classes.
- .lsx-geo-ip
- .lsx-geo-country
- .lsx-geo-region
- .lsx-geo-zip-code
- .lsx-geo-metro-code
- .lsx-geo-city
- .lsx-geo-latitude
- .lsx-geo-longitude


## Nav Menu Filters
Firstly create a menu and assign it to a menu location.  You will add a custom link to the menu and several child menu items, so it creates a drop down on the frontend.
- .lsx-geo
- .lsx-geo-parent
- .lsx-geo-default
- .lsx-geo-{country_code}  e.g "lsx-geo-za"
- .lsx-geo-ex-{country_code} e.g "lsx-geo-ex-us"

## Works with the LSX Theme
Our [theme](https://lsx.lsdev.biz/) works perfectly with the Geo Content plugin.

## It's free, and always will be.
We’re firm believers in open source - that’s why we’re releasing the Geo Content plugin for free, forever.

## Support
We offer premium support for this plugin. Premium support that can be purchased via [lsdev.biz](https://www.lsdev.biz/).

## Installation
1. Log in to your WordPress website (www.yourwebsiteurl.com/wp-admin).
2. Navigate to “Plugins”, and select “Add New”.
3. Upload the .zip file you downloaded and click install now.
4. When the installation is complete, Activate your plugin.
5. After installation, you can use the Geo content plugin with shortcakes or directly in to the code or caldera form.

## Frequently Asked Questions
### Where can I find the Geo Content plugin documentation and user guides?
For help setting up and configuring the Geo Content plugin please refer to our [user guide](https://www.lsdev.biz/documentation/lsx/geo-content-extension/)
### Where can I get support or talk to other users
For help with add-ons from LightSpeed, see our support [package plan](https://www.lsdev.biz/website-packages/)
### Will the Geo Content plugin work with my theme
No; the Geo Content plugin will not work with any theme, it requires LSX Theme to make it match nicely. Please see our [codex](https://www.lsdev.biz/documentation/lsx/geo-content-extension/) for help.
### Where can I report bugs or contribute to the project?
Bugs can be reported either in our support forum or preferably on the [to Search GitHub repository](https://github.com/lightspeeddevelopment/to/issues).
### The to Search plugin is awesome! Can I contribute?
Yes you can! Join in on our [GitHub repository](https://github.com/lightspeeddevelopment/lsx-geo-content) :)

## Like what you see?
<a href="https://www.lsdev.biz/contact/"><img src="https://www.lsdev.biz/wp-content/uploads/2020/02/work-with-lightspeed.png" width="850" alt="Work with us at LightSpeed"></a>
