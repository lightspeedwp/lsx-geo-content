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


# MaxMind GeoIP Legacy PHP API #

## Important Note ##

This API is for the GeoIP Legacy format (dat). To read the MaxMind DB format
(mmdb) used by GeoIP2, please see
[our GeoIP2 PHP API](https://github.com/maxmind/GeoIP2-php).

## Requirements ##

This module has no external dependencies. You only need a MaxMind GeoIP
database. To download a free GeoLite Legacy Country database, please see
our [GeoLite Legacy page](http://dev.maxmind.com/geoip/legacy/geolite).

## Install via Composer ##

We recommend installing this package with [Composer](http://getcomposer.org/).

### Download Composer ###

To download Composer, run in the root directory of your project:

```bash
curl -sS https://getcomposer.org/installer | php
```

You should now have the file `composer.phar` in your project directory.

### Install Dependencies ###

Run in your project root:

```
php composer.phar require geoip/geoip:~1.16
```

You should now have the files `composer.json` and `composer.lock` as well as
the directory `vendor` in your project directory. If you use a version control
system, `composer.json` should be added to it.

### Require Autoloader ###

After installing the dependencies, you need to require the Composer autoloader
from your code:

```php
require 'vendor/autoload.php';
```

## Install without Composer ##

Place the 'geoip.inc' file in the `include_path` as specified in your
`php.ini` file or place it in the same directory as your PHP scripts.


## IP Geolocation Usage ##

IP geolocation is inherently imprecise. Locations are often near the center of
the population. Any location provided by a GeoIP database should not be used to
identify a particular address or household.

## Usage ##

Gets country name by hostname :

```php
<?php

require 'vendor/autoload.php';

$gi = geoip_open("/usr/local/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);

echo geoip_country_code_by_addr($gi, "24.24.24.24") . "\t" .
     geoip_country_name_by_addr($gi, "24.24.24.24") . "\n";
echo geoip_country_code_by_addr($gi, "80.24.24.24") . "\t" .
     geoip_country_name_by_addr($gi, "80.24.24.24") . "\n";

geoip_close($gi);
```

## Memory Caching ##

To enable memory caching, pass `GEOIP_SHARED_MEMORY` or `GEOIP_MEMORY_CACHE`
as the second argument of `geoip_open`.

`GEOIP_SHARED_MEMORY` requires php >= 4.0.4 compiled with `--enable-shmop`
configure time.  See (http://us2.php.net/manual/en/ref.shmop.php).
In addition, you should call `geoip_load_shared_mem` before calling
`geoip_open`.  See `sample_city.php` for an example of shared memory caching.

## Support ##

For help with this API or our databases, please see [our support page]
(http://www.maxmind.com/en/support).

## Copyright and License ##

This software is Copyright (c) 2016 by MaxMind, Inc.

This is free software, licensed under the GNU Lesser General Public License
version 2.1 or later.

## Thanks ##

Thanks to Jim Winstead.

## Like what you see?
<a href="https://www.lsdev.biz/contact/"><img src="https://www.lsdev.biz/wp-content/uploads/2020/02/work-with-lightspeed.png" width="850" alt="Work with us at LightSpeed"></a>
