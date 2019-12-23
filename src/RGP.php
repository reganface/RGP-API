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
		$path = "/checkins/facility/$facility_code";
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
	// when $facilities is omitted, all facilities are returned
	// when it's an array of facility codes, just those facilities are returned
	// if it's a string of a single facility code, just that facility is returned
	public function get_setting($name, $facilities=null) {
		$path = is_string($facilities) ? "/settings/facility/$facilities" : "/settings";
		$params = [
			"name" => $name,
			"facility" => is_array($facilities) ? $facilities : null
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response);

	}



	/***************
	* VERSIONS
	***************/


	// gets the last run version
	// when $facilities is omitted, all facilities are returned
	// when it's an array of facility codes, just those facilities are returned
	// if it's a string of a single facility code, just that facility is returned
	public function get_version($facilities=null) {
		$path = is_string($facilities) ? "/versions/facility/$facilities" : "/versions";
		$params = [
			"facility" => is_array($facilities) ? $facilities : null
		];

		$response = $this->_make_call($path, $params);
		return $this->_generate_result($response);

	}


	/***************
	* PAGES
	***************/


	// get the data from a specific page for paginated results
	public function fetch_page($page) {
		// remove base URL from supplied path so we can use _make_call()
		$path = str_replace(self::API_URL, "", $page);

		$response = $this->_make_call($path);
		return $this->_generate_result($response);

	}




	/*************************
	*
	* PRIVATE METHODS
	*
	**************************/


	// contains the actual network request to connect to the API
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
				throw new \Exception("RGP Library Internal Error: Invalid request type - {$method}");
				break;
		}

		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

		// handle any http codes that are not success
		if ($http_code < 200 || $http_code > 299) {
			$error = $this->_http_code_text($http_code);
			throw new \Exception("RGP Request Error: $error");
		}

		// convert json to array
		// ping request just returns a string, so if ping was called, return the result without trying to decode
		if ($path === "/ping")
			return $result;

		$result_array = json_decode($result, true);

		// check to see if RGP returned any errors
		if ($result_array["rgpApiError"] || $result_array["rgpApiType"] === "error") {
			$error = "{$result_array["status"]} - {$result_array["message"]}";
			throw new \Exception("RGP Response Error: $error");
		}

		return $result_array;
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


	// Provide http status code information
	private function _http_code_text($http_code) {
		switch($http_code) {
			case 100: $text = "$http_code - Continue"; break;
			case 101: $text = "$http_code - Switching Protocols"; break;
			case 200: $text = "$http_code - OK"; break;
			case 201: $text = "$http_code - Created"; break;
			case 202: $text = "$http_code - Accepted"; break;
			case 203: $text = "$http_code - Non-Authoritative Information"; break;
			case 204: $text = "$http_code - No Content"; break;
			case 205: $text = "$http_code - Reset Content"; break;
			case 206: $text = "$http_code - Partial Content"; break;
			case 300: $text = "$http_code - Multiple Choices"; break;
			case 301: $text = "$http_code - Moved Permanently"; break;
			case 302: $text = "$http_code - Moved Temporarily"; break;
			case 303: $text = "$http_code - See Other"; break;
			case 304: $text = "$http_code - Not Modified"; break;
			case 305: $text = "$http_code - Use Proxy"; break;
			case 400: $text = "$http_code - Bad Request"; break;
			case 401: $text = "$http_code - Unauthorized"; break;
			case 402: $text = "$http_code - Payment Required"; break;
			case 403: $text = "$http_code - Forbidden"; break;
			case 404: $text = "$http_code - Not Found"; break;
			case 405: $text = "$http_code - Method Not Allowed"; break;
			case 406: $text = "$http_code - Not Acceptable"; break;
			case 407: $text = "$http_code - Proxy Authentication Required"; break;
			case 408: $text = "$http_code - Request Time-out"; break;
			case 409: $text = "$http_code - Conflict"; break;
			case 410: $text = "$http_code - Gone"; break;
			case 411: $text = "$http_code - Length Required"; break;
			case 412: $text = "$http_code - Precondition Failed"; break;
			case 413: $text = "$http_code - Request Entity Too Large"; break;
			case 414: $text = "$http_code - Request-URI Too Large"; break;
			case 415: $text = "$http_code - Unsupported Media Type"; break;
			case 500: $text = "$http_code - Internal Server Error"; break;
			case 501: $text = "$http_code - Not Implemented"; break;
			case 502: $text = "$http_code - Bad Gateway"; break;
			case 503: $text = "$http_code - Service Unavailable"; break;
			case 504: $text = "$http_code - Gateway Time-out"; break;
			case 505: $text = "$http_code - HTTP Version not supported"; break;
			default: $text = $http_code; break;
		}

		return $text;
	}


}



?>
