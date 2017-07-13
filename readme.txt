== LSX Geo Content ==

=== Functionality ===
* Detect you IP and the Protocol Version its using (cached for 1 hour)

=== Caldera Forms ===
You can create 2 types of fields. You just need to call the field "Country".
* Text
* DropDown (providing you populate the field with the Country Name or the Name and Codes)

If you add the following "custom class" to a field, LSX Geo Content will compare the values to see if there is a match and filter the field appropriately

* .lsx-geo-ip
* .lsx-geo-country
* .lsx-geo-region
* .lsx-geo-zip-code
* .lsx-geo-metro-code
* .lsx-geo-city
* .lsx-geo-latitude
* .lsx-geo-longitude