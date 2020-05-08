<?php

// If composer was used, just include the autoload file
require __DIR__ . '/vendor/autoload.php';

// If the class file was downloaded, you will need to include it directly.
// require "/path/to/RGP.php";

// Add your API Username and API Key here.
$api_username = "apiname";
$api_key = "apikey";

// Exceptions are thrown on any errors, so wrap the code in a try/catch block.
try {
	// Instantiate the class with the api username/key.
	$rgp = new reganface\RGP\RGP($api_username, $api_key);

	// Get some data.  In this case all of your facilities.
	$result = $rgp->get('/facilities');

	// The data for all endpoints is returned in the "data" key.
	// Response info is returned in the "response" key.
	$facilities = $result["data"];

	// Do stuff with the data.  In this case, list all facilities and their facility code
	$first_facility = "";
	echo "Facilities:\n";
	if (!empty($facilities)) {
		foreach($facilities as $index => $facility) {
			$first_facility = $first_facility ?: $facility["code"];		// saving the first facility returned for later
			echo "{$facility["code"]} - {$facility["name"]}\n";			// output list of locations
		}
	}

	// get check-ins for the first facility returned above
	$params = [
		"startDateTime" => "2019-01-01 00:00:00",
		"endDateTime" => "2019-01-01 23:59:59",
		"customerDetails" => true
		/* "page" => 3	// to get a different page, you'd need to include the page number here */
	];
	$result = $rgp->get("/checkins/facility/{$first_facility}", $params);

	// determin what section of check-ins is being displayed
	$total = $result["pages"]["itemTotal"];
	$limit = $result["pages"]["itemPage"];
	$start = (($result["pages"]["pageCurrent"] - 1) * $limit) + 1;
	$end = min($start + $limit - 1, $total);

	// list this group of check-ins
	echo "\n{$start} to {$end} of {$total} Check-Ins\n";
	if (!empty($result["data"])) {
		foreach($result["data"] as $checkin) {
			echo "{$checkin["checkin"]["postDate"]} - {$checkin["customer"]["lastName"]}, {$checkin["customer"]["firstName"]}\n";
		}
	}

} catch (Exception $e) {
	// handle any errors here
	echo $e->getMessage();
}

?>
