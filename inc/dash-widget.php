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
			echo 'post<pre>';
			print_r($_GET);
			echo '</pre>';
		}

		public function render_dashboard_widget() {
			$db_ir_options = get_option( 'db_ir_options' );
			echo 'from options<pre>';
			print_r($db_ir_options );
			echo '</pre>';
			echo 'working';
			printf( __( 'Developer Fuel must be configured to work properly. Please <a href="%s">click here</a> to configure it now!', 'dbimagereplace' ), add_query_arg( array( 'edit' => 'dbimagereplace' ) ) );
		}

		public function configure_dashboard_widget() {
			echo 'dfhadjshfjshfksajdhfjsdkahfljshafjhsdajfhasljfhconfig';

			$array_for_saving = array();

			foreach ( $this->image_dirs as $key => $value) {
				echo '<input type="checkbox" name="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value, 'dbimagereplace' ) . '</br>';
			}
		    echo '<input type="hidden" name="dbimagereplace_save" value="true" />';

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