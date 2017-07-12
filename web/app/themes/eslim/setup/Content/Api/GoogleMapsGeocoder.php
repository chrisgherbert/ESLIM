<?php

namespace Content\Api;

use Curl\Curl;

class GoogleMapsGeocoder {

	public $api_key;

	public function __construct($api_key){
		$this->api_key = $api_key;
	}

	public function geocode_address($address_string){

		// There's some weirdness in how we're handling the custom fields 
		// that trigger these geocoding lookups which could trigger multiple 
		// redundant calls.  While the free quota is relatively generous, just 
		// to be safe we should transient cache these.  A hash of the address 
		// can be the key.
		$cache_key = 'geo_' . md5($address_string);

		$cached_response = get_transient($cache_key);

		if ($cached_response !== false){
			return $cached_response;
		}

		$base_url = "https://maps.googleapis.com/maps/api/geocode/json";

		$params = array(
			'key' => $this->api_key,
			'address' => $address_string
		);

		$curl = new Curl;

		$curl->get($base_url, $params);

		error_log('Made Geocode request: ' . $curl->url);

		if ($curl->error) {
			error_log(
				get_called_class() . ' / Error Geocoding address : ' . $curl->errorCode . ': ' . $curl->errorMessage
			);
		} else {

			$response = $curl->response;

			if (isset($response->results) && $response->results){

				$geocoded_info = $response->results[0];

				set_transient($cache_key, $geocoded_info, MONTH_IN_SECONDS); // Cache for one month

				return $geocoded_info;

			}

		}

	}

	public function get_coords_from_address($address_string){

		$info = $this->geocode_address($address_string);

		if ($info){

			$info = $info;

			$latitude = $info->geometry->location->lat;
			$longitude = $info->geometry->location->lng;

			$coords = array(
				'lat' => $latitude,
				'lng' => $longitude
			);

			return $coords;

		}

	}

}

