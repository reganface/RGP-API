# Rock Gym Pro API
Rock Gym Pro has released a basic API as of December 2019.  This PHP class aims to make it easy to access all available endpoints that have been provided.  RGP has made it clear that future development will be dependent on community interest and usage, so get coding!  If there is some functionality you'd like to see added to the API, let RGP support know, as this will help guide development.

## API Documentation
You can view RGP's documentation of their API here: [https://api.rockgympro.com](https://api.rockgympro.com)

## API Keys
You will need to generate an API key before being able to access the API.  This [Google Doc](https://docs.google.com/document/d/1J_r1QkUphSsaPa-KdqsUv0xd7r39qp3M4169ouv6rXc/edit) has instructions on how to generate your key for both cloud and locally hosted servers.

## Installation
There are two ways to use this library.  You can install it as a dependency with [Composer](https://getcomposer.org/), or you can download RGP.php manually and include it in your code.

### Composer
```bash
composer require reganface/rgp
```
Include composer's autoload file to load all of your dependencies
```php
require __DIR__ . '/vendor/autoload.php';
```
### Download
Save RGP.php to your project folder and include it in your project
```php
require "/path/to/RGP.php";
```

### cURL
This class requires curl to be installed and enabled on the version of PHP that you are using.  PHP will throw errors if curl is not accessible.

## Basic Usage
When instantiating the class, you just need to include your api username and api key.  Exceptions will be thrown on any errors, so make sure to keep code inside a try/catch block.
```php
$api_username = "apiname";	// add your api username here
$api_key = "apikey";		// add your api key here

try {
	$rgp = new reganface\RGP\RGP($api_username, $api_key);

	// do stuff

} catch (Exception $e) {
	// handle errors
}
```

The structure of the data returned by any method will differ slightly from what RGP returns if you were to access the API directly.  This is done to keep the meta data of the response separate from the data you requested, as well as to keep a consistent structure across all methods.  The structure looks like this:
```php
[
	"data"  => [],			// The requested data
	"response"  => [],		// response information
	"pages"  => []			// array of page URLs (if applicable)
]
```

### Example
```php
// Get some data. In this case all of your facilities.
$result = $rgp->get_facilities();

// The data will always be found in the "data" key.
$facilities  =  $result["data"];

// Do stuff with the data.
if (!empty($facilities)) {
	foreach($facilities  as  $facility) {
		echo  "{$facility["code"]} - {$facility["name"]}\n";
	}
}
```

# Methods

## Bookings

### get_customer_bookings()
```php
get_customer_bookings (string $customer_id, string $start_date, string $end_date [, int $limit])
```
Returns all bookings by a specific customer between the start and end dates

**customer_id**
The guid of the customer you want to search for

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

---

### get_facility_bookings()
```php
get_facility_bookings(string $facility_code, string $start_date, string $end_date [, int $limit [, int $include_void_invoices [, string $customer_id ]]])
```
Returns all bookings for a given facility within the date range.

**facility_code**
The three character facility code of the facility you are accessing

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

**include_void_invoices**
Set to 0 to omit void invoices.  Default: 1

**customer_id**
Filter results by a specific customer

---

### get_booking()
```php
get_booking(string $facility_code, int $booking_id)
```
Returns the details of a specific booking

**facility_code**
The three character facility code of the facility you are accessing

**booking_id**
The numeric booking id

---

## Check-Ins

### get_customer_checkins()
```php
get_customer_checkins(string $customer_id, string $start_date, string $end_date [, int $limit])
```
Returns all check-ins of a specific customer between the provided date range

**customer_id**
The guid of the customer you want to search for.

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

---

### get_facility_checkins()
```php
get_facility_checkins(string $facility_code, string $start_date, string $end_date [, int $limit [, string $customer_id ]])
```
Returns check-ins from a specific facility, optionally filtered down to a single customer.

**facility_code**
The three character facility code of the facility you are accessing

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

**customer_id**
Filter results by a specific customer

---

### get_checkin()
```php
get_checkin(string $facility_code, int $checkin_id)
```
Returns details about a specific check-in

**facility_code**
The three character facility code of the facility you are accessing

**checkin_id**
Numeric check-in id

---

## Customers

### get_customer()
```php
get_customer(string $customer_id)
```
Returns details about a specific customer

**customer_id**
The guid of the customer record that you want.

---

## Debug

### get_debug()
```php
get_debug(string $request_id)
```
The API documentation says this returns debug information for a specific request ID, however, it's returning 401 Unauthorized for each of my attempts so far.

**request_id**
This is the request id sent back with the result set of any request.

---

## Facilities

## get_facilities()
```php
get_facilities()
```
Returns all facilities linked to your API key along with their facility codes.

---

## Invoices

### get_customer_invoices()
```php
get_customer_invoices(string $customer_id, string $start_date, string $end_date [, int $limit [, int $include_void_invoices ]])
```
Returns all invoices associated with a specific customer within the provided date range.

**customer_id**
The guid of your customer

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

**include_void_invoices**
Set to 0 to omit void invoices.  Default: 1

---

### get_facility_invoices()
```php
get_facility_invoices(string $facility_code, string $start_date, string $end_date [, int $limit [, int $include_void_invoices [, string $customer_id ]]])
```
Returns all invoices at a specific facility, optionally filtered down to a specific customer.

**facility_code**
The three character code for your facility.

**start_date**
Start of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**end_date**
End of the search range. Format: `YYYY-MM-DD HH:MM:SS`

**limit**
Max results per page.  Default: 100

**include_void_invoices**
Set to 0 to omit void invoices.  Default: 1

**customer_id**
The guid of your customer.

---

### get_invoice()
```php
get_invoice(string $facility_code, int $invoice_id)
```
Returns details about a single invoice.

**facility_code**
The three character code for your facility

**invoice_id**
Numeric id for the selected invoice.

---

## Ping

### ping()
```php
ping()
```
Returns the string "pong"

---

### me()
```php
me()
```
Returns basic token information

---

## Settings

### get_setting()
```php
get_setting(string $name [, mixed $facilities ])
```
Returns the value of the requested setting.

**name**
The name of the setting.

**facilities**
If omitted, the value of this setting at all facilities will be returned.
If a single facility code is provided as a string, only that facility will be returned.
If multiple facilities codes are provided in an array, just those facilities are returned

---

## Versions

### get_version()
```php
get_version([ mixed $facilities ])
```
Returns RGP version information for the selected facilities.

**facilities**
If omitted, the version info at all facilities will be returned.
If a single facility code is provided as a string, only that facility will be returned.
If multiple facilities codes are provided in an array, just those facilities are returned

---

## Pages

### fetch_page()
```php
fetch_page(string $page)
```
`fetch_page` allows you to access paginated data returned by other methods.  All methods that contain an optional `limit` parameter will return data in pages.

**page**
This is a page URL that is returned from any method that has a `limit` parameter.

```php
// Example
$result = $rgp->get_facility_checkins("AAA", "2019-11-01 00:00:00", "2019-11-30 23:59:59");

// get the URL of the last page in the result set
$index = count($result["pages"]) - 1;
$last_page = $result["pages"][$index];

// fetch data from this page
$last_page_result = $rgp->fetch_page($last_page);
```
