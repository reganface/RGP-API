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
