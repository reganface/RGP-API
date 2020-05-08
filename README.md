# Rock Gym Pro API
Rock Gym Pro has released a basic API as of December 2019.  This PHP class aims to make it easy to access all available endpoints that have been provided.  RGP has made it clear that future development will be dependent on community interest and usage, so get coding!  If there is some functionality you'd like to see added to the API, let RGP support know, as this will help guide development.

## API Documentation
You can view RGP's documentation of their API here: [https://api.rockgympro.com](https://api.rockgympro.com)

## API Keys
You will need to generate an API key before being able to access the API.  This [Google Doc](https://docs.google.com/document/d/1J_r1QkUphSsaPa-KdqsUv0xd7r39qp3M4169ouv6rXc/edit) from RGP has instructions on how to generate your key for both cloud and locally hosted servers.

# Version 1.0.0 of This Library
The scope of this library has been reduced to provide just the basic wrapper and some small tweaks on the response structure.  Previously, there were separate methods for each endpoint of the API.  There have been several changes to the API in that time which broke this library in some areas.  The reduction in scope should future proof it a little better, as it doesn't try to do too much.

## Installation
There are two ways to use this library.  You can install it as a dependency with [Composer](https://getcomposer.org/), or you can download RGP.php from this repository and include it in your code.

### Composer
```bash
composer require reganface/rgp
```
Include composer's autoload file at the top of your project to load all of your dependencies.
```php
require __DIR__ . '/vendor/autoload.php';
```
### Download
Save RGP.php to your project folder and include it in your project.
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

The structure of the data returned by `get()` will differ slightly from what RGP returns if you were to access the API directly.  This is done to keep the meta data of the response separate from the data you requested.  The structure looks like this:
```php
[
	"data"  => [],			// The requested data
	"response"  => [],		// response information
	"pages"  => []			// pagination information (if applicable)
]
```
If you would prefer to have the data exactly as the API returns, you can use `get_raw()` instead.

### Example
```php
// Get some data. In this case, a list of all your facilities.
$result = $rgp->get("/facilities");

// The data will always be found in the "data" key.
$facilities  =  $result["data"];

// Output a list of each facility's three character code and the facility name.
if (!empty($facilities)) {
	foreach($facilities as $facility) {
		echo  "{$facility["code"]} - {$facility["name"]}\n";
	}
}
```

# Methods

### get()
```php
get (string $path [, array $params])
```
Returns data from the API with a slightly altered data structure as seen below:
```php
[
	"data" => array,			// this is the data you are looking for
	"response" => array,		// meta data about the response
	"pages" => [				// this will be here for any endpoints that return paginated data
		"itemTotal" => int,		// total number of results
		"itemPage" => int,		// number of results per page
		"pageTotal" => int,		// total number of pages
		"pageCurrent" => int	// the current page that was just returned
	]
]
```

**path**\
This is the path of the API endpoint.  The list of all current endpoints is available at https://api.rockgympro.com. *Note: do not include "/v1" in the path.*

**params**\
An associative array of any query paramaters you want to include with the call.

---

### get_raw()
```php
get_raw (string $path [, array $params])
```
This method is identical to `get()` except that it will return the data exactly how the API returns it.

---

### test()
```php
test ()
```
Tests the connection to the API.  Returns true if a connections can be made successfully.
