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
			// get standard HTTP code text
			$error = $this->_http_code_text($http_code);

			// get any error message from rgp
			$result_array = json_decode($result, true);
			$msg = $result_array["message"];

			throw new \Exception("RGP Request Error: $error - $msg");
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
			case 416: $text = "$http_code - Range Not Satisfiable"; break;
			case 417: $text = "$http_code - Expectation Failed"; break;
			case 418: $text = "$http_code - I'm a teapot"; break;
			case 421: $text = "$http_code - Misdirected Request"; break;
			case 422: $text = "$http_code - Unprocessable Entity"; break;
			case 423: $text = "$http_code - Locked"; break;
			case 424: $text = "$http_code - Failed Dependency"; break;
			case 425: $text = "$http_code - Too Early"; break;
			case 426: $text = "$http_code - Upgrade Required"; break;
			case 428: $text = "$http_code - Precondition Required"; break;
			case 429: $text = "$http_code - Too Many Requests"; break;
			case 431: $text = "$http_code - Request Header Fields Too Large"; break;
			case 451: $text = "$http_code - Unavailable For Legal Reasons"; break;
			case 500: $text = "$http_code - Internal Server Error"; break;
			case 501: $text = "$http_code - Not Implemented"; break;
			case 502: $text = "$http_code - Bad Gateway"; break;
			case 503: $text = "$http_code - Service Unavailable"; break;
			case 504: $text = "$http_code - Gateway Time-out"; break;
			case 505: $text = "$http_code - HTTP Version not supported"; break;
			case 506: $text = "$http_code - Variant Also Negotiates"; break;
			case 507: $text = "$http_code - Insufficient Storage"; break;
			case 508: $text = "$http_code - Loop Detected"; break;
			case 510: $text = "$http_code - Not Extended"; break;
			case 511: $text = "$http_code - Network Authentication Required"; break;

			default: $text = $http_code; break;
		}

		return $text;
	}


}



?>
