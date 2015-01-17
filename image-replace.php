<?php

/**
 * Image Replace
 *
 * @package Image Replace
 * @subpackage Main
 */

/**
 * Plugin Name: Image Replace
 * Plugin URI:
 * Description: Replacing your images
 * Author: Dan Beil
 * Author URI:
 * Version: 0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'DB_IMAGE_REPLACE_PATH', plugins_url( '/', __FILE__ ) );
define( 'DB_IMAGE_REPLACE_IMAGE_DIR_PATH', dirname( realpath( __FILE__ ) ).'/imgs/futurama/' );

if ( ! class_exists( 'DB_Image_Replace' ) ) {

	/**
	 * Filter the image yo!
	 *
	 **/
	class DB_Image_Replace {

		public $img_sizes = array();
		public $img_files = array();

		public function __construct() {
			add_action( 'init', array( $this, 'get_img_sizes' ) );
			add_action( 'init', array( $this, 'image_arrays' ) );
			add_filter( 'post_thumbnail_html', array( $this, 'image_src_filter' ), 99, 5 );

		}

		public function get_img_sizes() {
			global $_wp_additional_image_sizes;
			$this->img_sizes = $_wp_additional_image_sizes;
		}

		public function image_arrays() {
	       foreach(glob( DB_IMAGE_REPLACE_IMAGE_DIR_PATH .'*') as $filename){
			    $this->img_files[] = basename( $filename );
			}
			shuffle( $this->img_files );
		}

		public function image_src_filter( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
			$img_info = wp_get_attachment_image_src( $post_thumbnail_id, $size );
			$count = count( $this->img_files );
			$rand = rand( 0, $count - 1 );
			$html = '<img src="' . esc_url( DB_IMAGE_REPLACE_PATH . 'imgs/futurama/' . basename( $this->img_files[ $rand ] ) ) . '" width="' . intval( $img_info[1] ) . '" height="' . intval( $img_info[2] ) . '" />';
			return $html;
		}

	} // END class
	new DB_Image_Replace();

}
