<?php
if ( ! class_exists( 'DB_Image_Replace_Dash_Widget' ) ) {

	/**
	 * The Image Replace dashboard widget
	 *
	 * @package Image Replace
	 * @author Dan Beil
	 **/
	class DB_Image_Replace_Dash_Widget {

		/**
		 * Array keys correspond to image directories, i.e. imgs/futurama
		 */
		public $image_dirs = array(
			'futurama' => 'Futurama',
			'star-trek' => 'Star Trek',
		);

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
		}

		public function render_dashboard_widget() {
			$db_ir_options = get_option( 'db_ir_options' );
			$edit_text = sprintf( __( 'Update your Image Replace options <a href="%s">HERE</a>', 'dbimagereplace' ), add_query_arg( array( 'edit' => 'dbimagereplace' ) ) );

			if ( ! empty( $db_ir_options ) ) {
				echo '<h2>' . esc_html__( 'Your are currently using:' ) . '</h2>';
				echo '<ul>';
				foreach ( $db_ir_options as $key => $value) {
					echo '<li>' . esc_html( $value ) . '</li>';
				}
				echo '</ul>';
			}
			echo $edit_text;
		}

		public function configure_dashboard_widget() {
			$db_ir_options = get_option( 'db_ir_options' );
			$array_for_saving = array(); // needed for saving
			echo '<h2>' . esc_html__( 'Image Replace Options:' ) . '</h2>';

			// Options output
			foreach ( $this->image_dirs as $key => $value) {
				$checked = array_key_exists( $key, $db_ir_options) ? 'checked' : '';
				echo '<input type="checkbox" ' . $checked . ' name="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value, 'dbimagereplace' ) . '</br>';
			}
			echo '<input type="hidden" name="dbimagereplace_save" value="true" />';
			echo '<hr /><br />';

			// Creating array for saving so we dont save all $_POST data here
			if ( isset( $_POST['dbimagereplace_save'] ) ) {
				foreach ( $this->image_dirs as $key => $value) {
					if ( array_key_exists( $key, $_POST ) )
						$array_for_saving[ $key ] = $value;
				}
			}
			update_option( 'db_ir_options', $array_for_saving );

		}




	} // END class

	new DB_Image_Replace_Dash_Widget();
}