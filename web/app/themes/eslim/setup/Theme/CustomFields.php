<?php
/**
 * This class defines custom fields for our post types
 */

namespace Theme;

class CustomFields {

	/**
	 * Optional custom field prefix
	 * @var string
	 */
	protected $prefix;
	/**
	 * Array of box names - correspond to method names
	 * @var array
	 */
	protected $boxes = array(
		'program_address',
		'program_extended',
		'home_slider',
		'home_links'
	);

	/**
	 * Constructor - should fire off all metabox creation when called.
	 */
	public function __construct(){
		$this->add_show_on_filters();
		$this->load_boxes();
	}

	///////////////
	// Metaboxes //
	///////////////

	/**
	 * Loop through our boxes property and invoke the corresponding
	 * function that will define its metabox(es)
	 */
	protected function load_boxes(){
		if ($this->boxes && !empty($this->boxes)){
			foreach ($this->boxes as $box) {
				add_action('cmb2_admin_init', array($this, $box));
			}
		}
	}

	///////////////
	// Home Page //
	///////////////

	public function home_slider(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'home_slider',
			'title' => 'Slideshow',
			'object_types' => array('page'),
			'show_on' => array(
				'key' => 'front-page'
			)
		));

		$cmb2->add_field(array(
			'id' => 'slides',
			'name' => 'Slides',
			'type' => 'file_list'
		));

		// $group_id = $cmb2->add_field(array(
		// 	'id' => 'slides',
		// 	'name' => 'Slides',
		// 	'type' => 'group',
		// 	'options' => array(
		// 		'sortable' => true
		// 	)
		// ));

		// $cmb2->add_group_field($group_id, array(
		// 	'id' => 'link',
		// 	'name' => 'URL',
		// 	'type' => 'text'
		// ));

		// $cmb2->add_group_field($group_id, array(
		// 	'id' => 'image',
		// 	'name' => 'Image',
		// 	'type' => 'file',
		// 	'options' => array(
		// 		'url' => false
		// 	)
		// ));

	}

	public function home_links(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'home_links',
			'title' => 'Home Links',
			'object_types' => array('page'),
			'show_on' => array(
				'key' => 'front-page'
			)
		));

		$group_id = $cmb2->add_field(array(
			'id' => 'image_links',
			'name' => 'Home Image Links',
			'type' => 'group'
		));

		$cmb2->add_group_field($group_id, array(
			'id' => 'image',
			'name' => 'Image',
			'type' => 'file',
			'options' => array(
				'url' => false
			)
		));

		$cmb2->add_group_field($group_id, array(
			'id' => 'link',
			'name' => 'Link',
			'type' => 'text'
		));

	}

	//////////////
	// Programs //
	//////////////

	public function program_address(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'program_address',
			'title' => 'Address',
			'object_types' => array('program')
		));

		$cmb2->add_field(array(
			'id' => 'address_1',
			'name' => 'Address 1',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'address_2',
			'name' => 'Address 2',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'city',
			'name' => 'City',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'state',
			'name' => 'State',
			'type' => 'select',
			'options' => self::get_state_options()
		));

		$cmb2->add_field(array(
			'id' => 'zip',
			'name' => 'ZIP Code',
			'type' => 'text'
		));

	}

	public function program_extended(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'program_extended',
			'title' => 'Extended Information',
			'object_types' => array('program')
		));

		$cmb2->add_field(array(
			'id' => 'class_days',
			'name' => 'Class Days',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'class_hours',
			'name' => 'Class Hours',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'duration',
			'name' => 'Duration',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'childcare',
			'name' => 'Childcare',
			'type' => 'text'
		));

		$cmb2->add_field(array(
			'id' => 'registration_fee',
			'name' => 'Registration Fee',
			'type' => 'textarea_small',
		));

		$cmb2->add_field(array(
			'id' => 'registration_date',
			'name' => 'Registration Date',
			'type' => 'textarea_small',
		));

		$cmb2->add_field(array(
			'id' => 'wheelchair_accessible',
			'name' => 'Wheelchair accessible?',
			'type' => 'checkbox'
		));

	}

	/////////////////////
	// Reusable Fields //
	/////////////////////
	//
	// Easily reuse field definitions by passing in the
	// current $cmb2 object being manipulated

	/**
	 * Subtitle field
	 * @param  object $cmb2
	 */
	protected function subtitle_field($cmb2){
		return $cmb2->add_field( array(
			'id'       => 'subtitle',
			'name'     => 'Subtitle',
			'type'     => 'textarea_small',
			'desc'     => '(Optional) Enter a subtitle',
		) );
	}

	/**
	 * Featured Video URL field
	 */
	protected function featured_video_url_field($cmb2){
		return $cmb2->add_field( array(
			'id'       => 'featured_video_url',
			'name'     => 'Featured Video URL',
			'type'     => 'oembed',
			'desc'     => '(YouTube only) Add the URL of the YouTube video you would like featured. This will attempt to retrieve the thumbnail from YouTube and set it as the featured image.',
		) );
	}

	/////////////////////
	// Show On Filters //
	/////////////////////

	/**
	 * Create any custom show_on filters that we may want to utilize
	 */
	protected function add_show_on_filters(){
		add_filter('cmb2_show_on', array($this, 'show_on_front_page'), 10, 2);
	}

	public function show_on_front_page($display, $meta_box){

		// Move along if we haven't defined a "show_on" property
		if ( ! isset( $meta_box['show_on']['key'] ) ){
			return $display;
		}

		// Ignore if not the front-page "show_on"
		if ( 'front-page' !== $meta_box['show_on']['key'] ) {
			return $display;
		}

		$post_id = 0;

		// If we're showing it based on ID, get the current ID
		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
		} elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		}

		if ( ! $post_id ) {
			return false;
		}

		// Get ID of page set as front page, 0 if there isn't one
		$front_page = get_option( 'page_on_front' );

		// there is a front page set and we're on it!
		return $post_id == $front_page;

	}

	////////////
	// Static //
	////////////

	public static function get_state_options(){

		return array(
			'' => 'Choose State',
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District Of Columbia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		);

	}

}