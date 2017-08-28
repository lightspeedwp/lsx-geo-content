## LSX Geo Content

### Description
* Detect your IP address and the Protocol Version (v4 or v6) used. 
* Your country code  is then determined using the downloaded DB from https://dev.maxmind.com. This is cached for 1 hour.


### Shortcode
You can use any of the following inside a WordPress editor, or a shortcode enabled field.

```
[geo_content country="ZA"] You are in South Africa [/geo_content]

[geo_content country="US"] You are in the US [/geo_content]

```

### Template Tags
Replace country code with the 2 digit country code.
*  United States = US
*  United Kingdom = GB
*  South Africa = ZA

```
<?php 
    if ( lsx_geo_is_country( $country_code ) ) {
        // Do code
    }
?>
```

### Caldera Forms 
Add in one of the following custom CSS classes to the field you wish to pre-populate. The filters only work with the "text" and "dropdown" type classes.
* .lsx-geo-ip
* .lsx-geo-country


### Nav Menu Filters
Firstly create a menu and assign it to a menu location.  You will add a custom link to the menu and several child menu items, so it creates a drop down on the frontend.
* .lsx-geo
* .lsx-geo-parent
* .lsx-geo-default
* .lsx-geo-{country_code}  e.g "lsx-geo-za"
* .lsx-geo-ex-{country_code} e.g "lsx-geo-ex-us"