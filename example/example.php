<?php

// If composer was used, just include the autoload file
require __DIR__ . '/vendor/autoload.php';

// If the class file was downloaded, include it here.
// require "/path/to/RGP.php";

// Add your API Username and API Key here.
$api_username = "apiname";
$api_key = "apikey";

// Exceptions are thrown on any errors, so wrap the code in a try/catch block.
try {
	// Instantiate the class with the api username/key.
	$rgp = new reganface\RGP\RGP($api_username, $api_key);

	// Get some data.  In this case all of your facilities.
	$result = $rgp->get_facilities();

	// The data for all endpoints is returned in the "data" key.
	// Response info is returned in the "response" key.
	$facilities = $result["data"];

	// Do stuff with the data.
	if (!empty($facilities)) {
		foreach($facilities as $facility) {
			echo "{$facility["code"]} - {$facility["name"]}\n";
		}
	}

	// For endpoints that return paginated data, the first page is returned with this inital call.
	// Subsequent pages can be fetched manually with the fetch_page method.
	// Note: For the sake of brevity, the code below does not check for the existance of a second page before trying to fetch it.
	$facility_code = "AAA";		// Add your facility code here, or get it from the get_facilites() call above.
	$start_date = date("Y-m-d H:i:s", strtotime("yesterday"));
	$end_date = date("Y-m-d 23:59:59", strtotime("yesterday"));
	$result = $rgp->get_facility_checkins($facility_code, $start_date, $end_date);

	// The first page of result set is stored in "data" as before.
	$checkins_p1 = $result["data"];

	// The pages array contains the url for each page.
	$page_2_url = $result["pages"][1];

	// Fetch the data from the second page
	$result_2 = $rgp->fetch_page($page_2_url);

	// The data set in result_2 will be the second page of results
	$checkins_p2 = $result_2["data"];

} catch (Exception $e) {
	echo "Error: {$e->getMessage()}";
}

?>
