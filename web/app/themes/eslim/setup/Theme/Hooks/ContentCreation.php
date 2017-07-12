<?php

namespace Theme\Hooks;
use Content\Api\GoogleMapsGeocoder;

class ContentCreation {

	public function __construct(){

		// Geocode program addresses, save to custom fields
		add_action('save_post', array($this, 'geocode_address'), 10, 2);

	}

	/**
	 * When the address is updated, geocode the location and save the lat/lng to custom fields
	 */
	public function geocode_address($post_id, $post){

		if ($post->post_type != 'program'){
			return false;
		}

		if (!class_exists('\Content\Api\GoogleMapsGeocoder')){
			error_log('Missing \Content\Api\GoogleMapsGeocoder class.');
			return false;
		}

		$post = new \Timber\Post($post->ID);

		// I searched and search but COULD NOT find a hook that fires AFTER all 
		// the post meta has been saved, so I'm hooking into 'shutdown' - the 
		// final action before the PHP process stops.  Feels gross but seems 
		// to work.
		add_action('shutdown', function() use ($post){

			$parts = array(
				'address_1' => get_post_meta($post->ID, 'address_1', true),
				'address_2' => get_post_meta($post->ID, 'address_2', true),
				'city' => get_post_meta($post->ID, 'city', true),
				'state' => get_post_meta($post->ID, 'state', true),
				'zip' => get_post_meta($post->ID, 'zip', true),
			);

			// Make sure all the required parts are present
			$req_parts = array(
				'address_1',
				'city',
				'state',
			);

			$missing_parts = false;

			foreach ($req_parts as $req_part){
				if (!$parts[$req_part]){
					error_log('Missing ' . $req_part);
					$missing_parts = true;
				}
			}

			if ($missing_parts){
				error_log('Missing required address parts - cannot geocode address');
				return false;
			}

			// Create address string for Google.  Doesn't need to be perfect, it's pretty resilient
			$address_string = implode(' ', $parts);

			// Set up the geocoder with the API key (set in .env file in project root)
			$geocoder = new GoogleMapsGeocoder(getenv('GOOGLE_MAPS_GEOCODING_API_KEY'));

			$coords = $geocoder->get_coords_from_address($address_string);

			if ($coords && isset($coords['lat']) && isset($coords['lng'])){

				// Set custom fields
				$post->update('lat', $coords['lat']);
				$post->update('lng', $coords['lng']);

				return true;

			}

		});

	}

}