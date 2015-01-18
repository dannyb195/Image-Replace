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
define( 'DB_IMAGE_RELPACE_LOCAL_PATH', plugin_dir_path( __FILE__ ) );
echo DB_IMAGE_RELPACE_LOCAL_PATH;

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
			foreach ( glob( DB_IMAGE_REPLACE_IMAGE_DIR_PATH .'*') as $filename){
				$this->img_files[] = basename( $filename );
			}
			shuffle( $this->img_files );


		}

		public function image_src_filter( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
			// dealing with different $size array on single.php
			if ( is_array( $size ) && isset( $size[0] ) ) {
				$org_w = $size[0];
				$org_h = $size[1];
			} else {
				$org_w = $this->img_sizes[ $size ]['width'];
				$org_h = $this->img_sizes[ $size ]['height'];
			}

			$count = count( $this->img_files );
			$rand = rand( 0, $count - 1 );

			$image = DB_IMAGE_REPLACE_PATH . 'imgs/futurama/' . basename( $this->img_files[ $rand ] ); // the image to crop
			fopen( DB_IMAGE_RELPACE_LOCAL_PATH . 'imgs/temp/cropped_whatever.jpg', 'w' );
			$dest_image = 'imgs/temp/cropped_whatever.jpg'; // make sure the directory is writeable
			$img = imagecreatetruecolor( intval( $org_w ), intval( $org_h ) );
			$org_img = imagecreatefromjpeg( $image );
			$ims = getimagesize( $image );
			imagecopy( $img, $org_img, 0, 0, 20, 20, intval( $org_w ), intval( $org_h ) );
			imagejpeg( $img, DB_IMAGE_RELPACE_LOCAL_PATH . $dest_image, 90 );

			$img_info = wp_get_attachment_image_src( $post_thumbnail_id, $size );

			$html = '<img src="' . esc_url( DB_IMAGE_REPLACE_PATH . 'imgs/temp/' . basename( $dest_image ) ) . '" width="' . intval( $org_w ) . '" height="' . intval( $org_h ) . '" />';
			return $html;
		}

	} // END class
	new DB_Image_Replace();

}
