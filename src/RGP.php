<?php

namespace reganface\RGP;

/***************************************
*
* RGP API class
*
* API Documentation: https://api.rockgympro.com/
*
*
***************************************/



class RGP {
	const API_URL = "https://api.rockgympro.com/v1";	// base url for api calls
	private $_api_name;
	private $_api_key;


	// constructor
	public function __construct($api_name, $api_key) {
		if (!$api_name)
			throw new \Exception("Error: Missing API name");

		if (!$api_key)
			throw new \Exception("Error: Missing API key");

		$this->_api_name = $api_name;
		$this->_api_key = $api_key;
	}




	/***************
	* BOOKINGS
	***************/

	// gets bookings by a specific customer between start and end dates
	public function get_customer_bookings($customer_id, $start_date, $end_date, $limit=null) {
		$path = "/bookings/customer/$customer_id";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// gets bookings from a specific facility between start and end dates
	public function get_facility_bookings($facility_code, $start_date, $end_date, $limit=null, $include_void_invoices=1, $customer_id=null) {
		$path = "/bookings/facility/$facility_code";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit,
			"includeVoidInvoices" => $include_void_invoices,
			"customer" => $customer_id
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// gets booking details of specific booking
	public function get_booking($facility_code, $booking_id) {
		$path = "/bookings/facility/$facility_code/$booking_id";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}



	/***************
	* CHECK-INS
	***************/


	// gets all check-ins from a specific customer between start and end date
	public function get_customer_checkins($customer_id, $start_date, $end_date, $limit=null) {
		$path = "/checkins/customer/$customer_id";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// gets all check-ins from a specific facility between start and end date
	public function get_facility_checkins($facility_code, $start_date, $end_date, $limit=null, $customer_id=null) {
		$path = "/bookings/facility/$facility_code";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit,
			"customer" => $customer_id
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// get check-in details of single check-in
	public function get_checkin($facility_code, $checkin_id) {
		$path = "/checkins/facility/$facility_code/$checkin_id";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}




	/***************
	* CUSTOMERS
	***************/



	// gets a single customer's details
	public function get_customer($customer_id) {
		$path = "/customers/$customer_id";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}


	/***************
	* DEBUG
	***************/


	// gets debug information for a specific request id
	public function get_debug($request_id) {
		$path = "/debug/$request_id";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}


	/***************
	* FACILITIES
	***************/


	// gets all available facilities
	public function get_facilities() {
		$path = "/facilities";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);
	}



	/***************
	* INVOICES
	***************/


	// gets a customer's invoices between start and end date
	public function get_customer_invoices($customer_id, $start_date, $end_date, $limit=null, $include_void_invoices=1) {
		$path = "/invoices/customer/$customer_id";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit,
			"includeVoidInvoices" => $include_void_invoices
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// gets the invoices for a specific facility between start and end date
	public function get_facility_invoices($facility_code, $start_date, $end_date, $limit=null, $include_void_invoices=1, $customer_id=null) {
		$path = "/invoices/facility/$facility_code";
		$params = [
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"limit" => $limit,
			"includeVoidInvoices" => $include_void_invoices,
			"customer" => $customer_id
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}


	// gets the details of a specific invoice
	public function get_invoice($facility_code, $invoice_id) {
		$path = "/invoices/facility/$facility_code/$invoice_id";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}




	/***************
	* PING
	***************/


	// pings the API server
	public function ping() {
		$path = "/ping";

		return $this->_make_call($path);

	}


	// gets authentication token information
	public function me() {
		$path = "/me";

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}




	/***************
	* SETTINGS
	***************/


	// gets a single setting value from one or more facilities
	public function get_setting($name, $facilities=null) {
		$path = "/settings";
		$params = [
			"name" => $name,
			"facility" => implode(",", $facilities)
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}

	// TODO: there is another settings end point that just does a single facility.
	// will need to see what the differences are



	/***************
	* VERSIONS
	***************/


	// gets the last run version
	public function get_version($facilities=null) {
		$path = "/versions";
		$params = [
			"facility" => implode(",", $facilities)
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response, true);

	}

	// TODO: there is a get version for specific facility as well
	// need to look into this to see what the difference is



	/***************
	* PAGES
	***************/


	// get the data from a specific page for paginated results
	public function fetch_page($full_path) {
		// remove base URL from supplied path so we can use _make_call()
		$path = str_replace(self::API_URL, "", $full_path);

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}




	/*************************
	*
	* PRIVATE METHODS
	*
	**************************/


	// makes and API call to RGP
	private function _make_call($path, $params=null, $method="GET") {
		$token = base64_encode("{$this->_api_name}:{$this->_api_key}");
		$headers = [
			"Accept: */*",
			"Authorization: Basic $token"
		];

		// build curl request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// GET is the only method currently in use
		switch ($method) {
			case "GET":
				curl_setopt($ch, CURLOPT_URL, $this->_query_string(self::API_URL . $path, $params));
				break;

			// POST gets added here when it's supported

			default:
				throw new \Exception("Invalid request type: {$method}");
				break;
		}

		$result = curl_exec($ch);

		// TODO: handle error responses here

		// convert json to array
		// ping request just returns a string, so if ping was called, just return the result without decoding
		return $path !== "/ping" ? json_decode($result, true) : $result;
	}


	// create url query string for GET requests
	private function _query_string($path, $params) {
		if (empty($params))
			return $path;

		$query = http_build_query($params, null, "&", PHP_QUERY_RFC3986);
		return "{$path}?{$query}";
	}


	// split up the response array to give a consistent structure
	// RGP returns data with a different key name depending on what endpoint is called.
	// This method will normalize the returned array to always have data in the "data" key
	// The rest of the response information will be in the "response" key
	private function _split_response($response) {
		$key = $response["rgpApiType"];
		$data = $response[$key];
		unset($response[$key]);

		return [
			"data" => $data,
			"response" => $response
		];
	}


	// creates the final returned data array based on whether the call was paginated or not
	private function _generate_result($response, $paged=false) {
		if ($paged) {
			$result = $this->fetch_page($response["pages"][0]);	// get first page results
			$result["pages"] = $response["pages"];				// add list of pages to result
		} else {
			$result = $this->_split_response($response);
		}

		return $result;
	}


}



?>
