<?php
/**
 * Plugin Name:       Edge Resource Widget
 * Description:       Edge Resource Widget is created by Zain Hassan.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Zain Hassan
 * Author URI:        https://hassanzain.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       resource-widget 
*/

if(!defined('ABSPATH')){
    exit;
}


add_action( 'elementor/elements/categories_registered', 'custom_category_elementor_resource' );
add_action( 'elementor/widgets/register', 'register_resource_elementor_widgets' );
add_action('wp_ajax_all_post_category_wise', 'all_post_category_wise_callback');
add_action('wp_ajax_nopriv_all_post_category_wise', 'all_post_category_wise_callback');



function custom_category_elementor_resource( $elements_manager ) {

	$elements_manager->add_category(
		'el-custom',
		[
			'title' => esc_html__( 'Custom Widgets', 'resource-widget' ),
			'icon' => 'fa fa-plug',
		]
	);
}

function register_resource_elementor_widgets( $widgets_manager ) {
  require_once( __DIR__ . '/inc/resource.php' );
  $widgets_manager->register( new \resource_widget_elementore );
}


function all_post_category_wise_callback(){
	$posts_by_category = array();
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1
	);
	
	$query = new WP_Query($args);
	$posts = array();
	if ($query->have_posts()) { 
		while ($query->have_posts()) {
			$query->the_post();
			$post_id = get_the_ID();
			$post_title = get_the_title();
			$post_permalink = get_permalink();
			$post_image = get_the_post_thumbnail_url($post_id, 'full');
			
			// Get the category names
			$category_names = array();
			$categories = get_the_category();
			foreach ($categories as $category) {
				$slug = $category->slug;
				array_push($category_names, $category->name);
			}
			$category_names = implode(', ', $category_names);
	
			$post_data = array(
				'title' => $post_title,
				'permalink' => $post_permalink,
				'image' => $post_image,
				'categories' => $category_names
			);
		
			// Add the post data to the appropriate category in the $posts_by_category array
			foreach ($categories as $category) {
				$category_slug = $category->slug;
				if (!isset($posts_by_category[$category_slug])) {
					$posts_by_category[$category_slug] = array();
				}
				array_push($posts_by_category[$category_slug], $post_data);
			}
			
		}
	
		wp_reset_postdata();
	
		// Do something with the $posts array
	}

	echo json_encode($posts_by_category);
	die();
}
