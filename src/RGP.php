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
			throw new RGPException("Error: Missing API name");

		if (!$api_key)
			throw new RGPException("Error: Missing API key");

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

		return $this->_make_call($path, $params);
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

		return $this->_make_call($path, $params);
	}


	// gets booking details of specific booking
	public function get_booking($facility_code, $booking_id) {
		$path = "/bookings/facility/$facility_code/$booking_id";

		return $this->_make_call($path);
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

		return $this->_make_call($path, $params);
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

		return $this->_make_call($path, $params);
	}


	// get check-in details of single check-in
	public function get_checkin($facility_code, $checkin_id) {
		$path = "/checkins/facility/$facility_code/$checkin_id";

		return $this->_make_call($path);
	}




	/***************
	* CUSTOMERS
	***************/



	// gets a single customer's details
	public function get_customer($customer_id) {
		$path = "/customers/$customer_id";

		return $this->_make_call($path);
	}


	/***************
	* DEBUG
	***************/


	// gets debug information for a specific request id
	public function get_debug($request_id) {
		$path = "/debug/$request_id";

		return $this->_make_call($path);
	}


	/***************
	* FACILITIES
	***************/


	// gets all available facilities
	public function get_facilities() {
		$path = "/facilities";

		return $this->_make_call($path);
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

		return $this->_make_call($path, $params);
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

		return $this->_make_call($path, $params);
	}


	// gets the details of a specific invoice
	public function get_invoice($facility_code, $invoice_id) {
		$path = "/invoices/facility/$facility_code/$invoice_id";

		return $this->_make_call($path);
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
	public function get_auth_token_info() {
		$path = "/me";

		return $this->_make_call($path);
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

		return $this->_make_call($path, $params);
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

		return $this->_make_call($path, $params);
	}

	// TODO: there is a get version for specific facility as well
	// need to look into this to see what the difference is






	/*************************
	*
	* PRIVATE METHODS
	*
	**************************/


	// makes and API call to RGP
	private function _make_call($path, $params=null, $method="GET") {
		$full_path = API_URL . $path;

		// TODO: setup network call here
	}


}



?>
