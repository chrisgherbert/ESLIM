<?php
/**
 *  Template Name: Home Page
 */

$context = Timber::get_context();
$post = Timber::get_post($post->ID, 'bermanco\ExtendedTimberClasses\Post');

$context['post'] = $post;

// Enqueue Owl slider assets
add_action('wp_enqueue_scripts', function(){
	wp_enqueue_script('owl');
	wp_enqueue_style('owl');
});

// Set special OG tags for the home page
$context['open_graph'] = array(
	array(
		'key' => 'og:title',
		'value' => get_option('blogname'),
	),
	array(
		'key' => 'og:url',
		'value' => get_option('home'),
	),
	array(
		'key' => 'og:description',
		'value' => get_option('blogdescription'),
	),
);

// Pull in current posts
$context['posts'] = Timber::get_posts(
	array(
		'post_type' => 'post',
		'posts_per_page' => 9
	),
	'bermanco\ExtendedTimberClasses\Post'
);

// Slides

$slides_meta = $post->meta('slides');

if ($slides_meta){

	$context['slides'] = array();

	foreach ($slides_meta as $key => $value) {
		$context['slides'][] = new Timber\Image($key);
	}

}

// Image Links

$image_links_meta = $post->meta('image_links');

if ($image_links_meta){

	$context['image_links'] = array();

	foreach ($image_links_meta as $item){

		$data = array();

		$data['link'] = $item['link'];
		$data['image'] = new Timber\Image($item['image_id']);

		$context['image_links'][] = $data;

	}

}

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context, false, TimberLoader::CACHE_NONE );
