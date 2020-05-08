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
	private $_token;

	// constructor
	public function __construct($api_name, $api_key) {
		if (!$api_name)
			throw new \Exception("Error: Missing API name");

		if (!$api_key)
			throw new \Exception("Error: Missing API key");

		$this->_token = base64_encode("{$api_name}:{$api_key}");
	}


	/*************************
	*
	* PUBLIC METHODS
	*
	**************************/


	public function get($path, $params=null) {
		$path = $this->_normalize_path($path);
		$response = $this->_make_call($path, $params);
		return $this->_split_response($response);
	}


	// don't transform response object
	public function get_raw($path, $params=null) {
		$path = $this->_normalize_path($path);
		return $this->_make_call($path, $params);
	}


	// simple connection test using /ping
	public function test() {
		$result = $this->_make_call("/ping");
		return $result === "pong";
	}




	/*************************
	*
	* PRIVATE METHODS
	*
	**************************/


	// contains the actual network request to connect to the API
	private function _make_call($path, $params=null, $method="GET") {
		$headers = [
			"Accept: */*",
			"Authorization: Basic {$this->_token}"
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
				throw new \Exception("Internal Error: Invalid request type - {$method}");
				break;
		}

		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

		// handle any http codes that are not success
		if ($http_code < 200 || $http_code > 299) {
			// get any error message from rgp
			$result_array = json_decode($result, true);
			$msg = $result_array["message"];

			throw new \Exception("RGP Request Error: $http_code - $msg");
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
		// if $response is just a string, return it. the /ping endpoint does this
		if (is_string($response)) return $response;

		$key = $response["rgpApiType"];
		$data = $response[$key];
		unset($response[$key]);

		$result = [
			"data" => $data,
			"response" => $response
		];

		if (isset($response["rgpApiPaging"])) {
			$result["pages"] = $response["rgpApiPaging"];
			unset($result["response"]["rgpApiPaging"]);
		}

		return $result;
	}


	// ensure the provided path is trimmed and has a leading slash
	private function _normalize_path($path) {
		$path = trim($path);
		if (substr($path, 0, 1) === "/") return $path;
		else return "/{$path}";
	}


}



?>
