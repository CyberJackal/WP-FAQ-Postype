<?php
/*
Plugin Name: LH FAQ Post Type
Description: Simple post type for frequently asked questions
Version: 1.0
Author: paulbarnett
License: GPLv2 or later
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

if( ! class_exists('FAQ_Posttype') ){

	class FAQ_Posttype {

		public function __construct() {

			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			add_action( 'init', array( $this, 'create_post_type' ) );

			add_filter( 'archive_template',  array( $this, 'register_archive_template' ) );
			add_filter( 'single_template',  array( $this, 'register_single_template' ) );

			add_filter( 'enter_title_here', array( $this, 'change_default_title' ) );
		}

		public function activate() {
			flush_rewrite_rules();
		}

		public function deactivate(){
			flush_rewrite_rules();
		}

		public function create_post_type(){

			$labels = array(
				'name'               => __( 'FAQs', 'post type general name' ),
				'singular_name'      => __( 'FAQ', 'post type singular name' ),
				'menu_name'          => __( 'FAQs', 'admin menu' ),
				'name_admin_bar'     => __( 'FAQ', 'add new on admin bar' ),
				'add_new'            => __( 'Add New', 'book' ),
				'add_new_item'       => __( 'Add New FAQ' ),
				'new_item'           => __( 'New FAQ' ),
				'edit_item'          => __( 'Edit FAQ' ),
				'view_item'          => __( 'View FAQ' ),
				'all_items'          => __( 'All FAQs' ),
				'search_items'       => __( 'Search FAQs' ),
				'parent_item_colon'  => __( 'Parent FAQs:' ),
				'not_found'          => __( 'No FAQs found.' ),
				'not_found_in_trash' => __( 'No FAQs found in Trash.' )
			);

			$options = array(
				'labels' => $labels,
				'has_archive'  => true,
				'public'       => true,
				'rewrite' => array(
					'slug' => 'faqs',
				),
				'show_ui'      => true,
				'show_in_menu' => true,
				'supports' => array(
					'editor',
					'title',
          'excerpt'
				),
				'menu_icon' => 'dashicons-lightbulb',
				'show_in_rest' => true,
			);
			register_post_type( 'lh_faq', $options );

			$tax_options = array(
				'hierarchical'  => true,
				'labels' 	=> array(
					'name'          => __( 'Categories', '' ),
					'singular_name' => __( 'Category', '' ),
				),
				'public'        => true,
				'rewrite' => array(
					'slug' => 'faq-category',
				),
				'show_tagcloud' => false,
				'show_in_rest'       => true,
			);
			register_taxonomy( 'lh_faq_category', 'lh_faq', $tax_options );

		}

		//Update the default title placeholder
		function change_default_title( $title ){
	    $screen = get_current_screen();
	    if ( $screen->post_type == 'lh_faq' ){
	        $title = 'Enter the question here';
	    }
	    return $title;
		}

		//Add the required templates to display the post type
		static function register_archive_template( $template ) {
			global $wp_query, $post;

			if ( $post->post_type != "lh_faq" && $wp_query->query_vars['post_type'] != "lh_faq" )
				return $template;

			//Check theme folder first, then default to included plugin templates
			if( file_exists( get_template_directory() . '/lh-templates/faq-templates/archive-faq.php' ) ){

				return get_template_directory() . '/lh-templates/faq-templates/archive-faq.php';

			}elseif( file_exists( plugin_dir_path( __FILE__ ) . 'faq-templates/archive-faq.php' ) ){

		    return plugin_dir_path( __FILE__ ) . 'faq-templates/archive-faq.php';

		  }

			return $template;
		}


		static function register_single_template( $template ) {
			global $post;

			if ($post->post_type != "lh_faq")
				return $template;

			if( file_exists( get_template_directory() . '/lh-templates/faq-templates/single-faq.php' ) ){

				return get_template_directory() . '/lh-templates/faq-templates/single-faq.php';

			}elseif( file_exists( plugin_dir_path( __FILE__ ) . 'faq-templates/single-faq.php') ){

        return plugin_dir_path( __FILE__ ) . 'faq-templates/single-faq.php';

	    }

	    return $template;
		}

	} //End Class

}

//Initialise plugin
if( class_exists('FAQ_Posttype') )
{
	$faq = new FAQ_Posttype();
}
