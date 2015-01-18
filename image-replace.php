<?php
/**
 * Plugin Name: Image Replace
 * Plugin URI:
 * Description: Replacing your images
 * Author: Dan Beil
 * Author URI: add_action_dan.me
 * Version: 0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DB_IMAGE_REPLACE_PATH', plugins_url( '/', __FILE__ ) );
define( 'DB_IMAGE_REPLACE_IMAGE_DIR_PATH', dirname( realpath( __FILE__ ) ).'/imgs/' );
define( 'DB_IMAGE_RELPACE_LOCAL_PATH', plugin_dir_path( __FILE__ ) );

include( 'inc/dash-widget.php' );

if ( ! class_exists( 'DB_Image_Replace' ) ) {

	/**
	 * Filter the image yo!
	 * We are gunna filter the_post_thumbnail and randomly display other images
	 *
	 **/
	class DB_Image_Replace {

		public $db_ir_options = '';



		public $img_sizes = array();
		public $img_files = array();

		public function __construct() {
			add_action( 'init', array( $this, 'get_img_sizes' ) );
			add_action( 'init', array( $this, 'image_arrays' ) );
			add_filter( 'post_thumbnail_html', array( $this, 'image_src_filter' ), 99, 5 );

			$this->db_ir_options = get_option( 'db_ir_options' );


		}

		public function get_img_sizes() {
			global $_wp_additional_image_sizes;
			$this->img_sizes = $_wp_additional_image_sizes;
		}

		public function image_arrays() {
			$db_ir_options = $this->db_ir_options = get_option( 'db_ir_options' );
			foreach ($db_ir_options as $key => $value) {
				$this->img_files[ $key ] = array();
				foreach ( glob( DB_IMAGE_REPLACE_IMAGE_DIR_PATH . '/' . $key .'/*' ) as $filename ){
					$this->img_files[ $key ][] = basename( $filename );
				}
				shuffle( $this->img_files[ $key ] );
			}
		}

		public function image_src_filter( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
			$db_ir_options = get_option( 'db_ir_options' );
			$rand_dir = array_rand( $db_ir_options, 1 );

			// dealing with different $size array on single.php
			if ( is_array( $size ) && isset( $size[0] ) ) {
				$target_w = $size[0];
				$target_h = $size[1];
			} else {
				$target_w = $this->img_sizes[ $size ]['width'];
				$target_h = $this->img_sizes[ $size ]['height'];
			}
			$count = count( $this->img_files[ $rand_dir ] );
			$rand = rand( 0, $count - 1 );
			$img_id_hash = hash( 'md5', basename( $this->img_files[ $rand_dir ][ $rand ] ) );

			if ( false === ( $html = get_transient( $img_id_hash . '-' . $target_w . 'x' . $target_h ) ) ) {


				$image = DB_IMAGE_REPLACE_PATH . 'imgs/' . $rand_dir . '/' . basename( $this->img_files[ $rand_dir ][ $rand ] ); // the image to crop
				$dest_image = 'imgs/temp/' . $img_id_hash . '-' . $target_w . 'x' . $target_h . '.jpg'; // make sure the directory is writeable
				$img = imagecreatetruecolor( intval( $target_w ), intval( $target_h ) );
				$org_img = imagecreatefromjpeg( $image );
				$original_size = getimagesize( $image );
				$target_x_start = ( $original_size[0] / 2 ) - ( $target_w / 2 );
				$target_y_start = ( $original_size[1] / 2 ) - ( $target_h / 2 );

				fopen( DB_IMAGE_RELPACE_LOCAL_PATH . 'imgs/temp/' . $img_id_hash . '-' . $target_w . 'x' . $target_h . '.jpg', 'w' );
				imagecopy( $img, $org_img, 0, 0, $target_x_start, $target_y_start, intval( $target_w ), intval( $target_h ) );
				imagejpeg( $img, DB_IMAGE_RELPACE_LOCAL_PATH . $dest_image, 90 );

				$html = '<img src="' . esc_url( DB_IMAGE_REPLACE_PATH . 'imgs/temp/' . basename( $dest_image ) ) . '" width="' . intval( $target_w ) . '" height="' . intval( $target_h ) . '" />';
				set_transient( $img_id_hash . '-' . $target_w . 'x' . $target_h, $html, 3 );
				return $html;
			} else {
				return $html;
			} // end transient check

		} // end image_src_filter()

	} // END class

	new DB_Image_Replace();

} // end if class_exists
