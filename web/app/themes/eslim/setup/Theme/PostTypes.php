<?php
/**
 * This class creates our post types
 */

namespace Theme;

class PostTypes {

	protected $types = array(
		'program'
	);

	public function __construct(){
		if ($this->types && !empty($this->types)){
			foreach ($this->types as $type) {
				$this->$type();
			}
		}
	}

	/**
	 * Sample "article" content type - used in place of the default "post" content type
	 * to allow a custom rewrite base.  This makes it easier to track blog/article traffic
	 * specifically in analytics software.  However, it also can complicate the development
	 * process.  Proceed with caution.
	 */
	// public function article(){

	// 	register_via_cpt_core(
	// 		array(
	// 			'Article', // Singular Name
	// 			'Articles', // Plural Name
	// 			'article' // Post Type Slug
	// 		),
	// 		array(
	// 			'menu_icon' => 'dashicons-admin-page', // Dashicon icon (Reference: https://developer.wordpress.org/resource/dashicons/)
	// 			'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	// 			'taxonomies' => array('category', 'post_tag'),
	// 			'has_archive' => true
	// 		)
	// 	);

	// }

	public function program(){

		register_via_cpt_core(
			array(
				'Program',
				'Programs',
				'program'
			),
			array(
				'menu_icon' => 'dashicons-admin-multisite',
				'supports' => array('title'),
				'has_archive' => false
			)
		);

	}

}