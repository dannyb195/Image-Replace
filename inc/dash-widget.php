<?php

echo 'dash widget';

if ( ! class_exists( 'DB_Image_Replace_Dash_Widget' ) ) {

	/**
	 * undocumented class
	 *
	 * @package default
	 * @author
	 **/
	class DB_Image_Replace_Dash_Widget {

		public function __construct() {
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		}

		public function add_dashboard_widget() {
			wp_add_dashboard_widget(
				'dbimagereplace',
				__( 'Image Replace', 'dbimagereplace' ),
				array( $this, 'render_dashboard_widget' ),
				array( $this, 'configure_dashboard_widget' )
			);
			echo 'post<pre>';
			print_r($_GET);
			echo '</pre>';
		}

		public function render_dashboard_widget() {
			$db_ir_options = get_option( 'db_ir_options' );
			echo 'working';
			echo '<form>';
				echo '<input type="checkbox" name="futurama" value="futurama">' . esc_html( 'Futurama', 'dbimagereplace' ) . '</br>';
				echo '<input type="checkbox" name="star-trek" value="start-trek">' . esc_html( 'Star Trek', 'dbimagereplace' ) . '</br>';
				echo '<input type="submit" value="' . esc_html( 'Save', 'dbimagereplace' ) . '">';
			echo '</form>';
		}

		public function configure_dashboard_widget() {
			echo 'config';
			echo 'post<pre>';
			print_r($_POST);
			echo '</pre>';
			// $db_ir_options = get_option( 'db_ir_options' );
			// echo 'working';
			// echo '<form action="' . home_url() . '">';
			// 	echo '<input type="checkbox" name="futurama" value="futurama">' . esc_html( 'Futurama', 'dbimagereplace' ) . '</br>';
			// 	echo '<input type="checkbox" name="star-trek" value="start-trek">' . esc_html( 'Star Trek', 'dbimagereplace' ) . '</br>';
			// 	echo '<input type="submit" value="' . esc_html( 'Save', 'dbimagereplace' ) . '">';
			// echo '</form>';
		}




	} // END class

	new DB_Image_Replace_Dash_Widget();
}