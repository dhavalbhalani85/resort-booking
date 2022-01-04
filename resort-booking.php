<?php
/**
 * Plugin Name:       Resort Booking
 * Plugin URI:        https://blackbuckresort.in/
 * Description:       Custom Resort booking form plugin with Payumoney payment gateway.
 * Version:           1.0.0
 * Author:            blackbuckresort
 * Author URI:        https://blackbuckresort.in/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       Resort-booking
 * Domain Path:       /languages
 */


/**
 * Main Class Resort_Booking
 */
class Resort_Booking{
	
	/**
	 * Resort Booking Constructor.
	 */
	function __construct(){
		
		$this->define_constants();
		$this->define_tables();
		$this->includes();
		$this->init_hooks();

	}

	/**
	 * Define WC Constants.
	 */
	private function define_constants() {
		define( 'RB_VERSION', '1.0.0' );
		define( 'RB_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'RB_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
	}

	/**
	 * Register custom tables within $wpdb object.
	 */
	private function define_tables() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();

		$resort_booking_table = $wpdb->prefix . 'resort_booking';

		$sql = "CREATE TABLE IF NOT EXISTS $resort_booking_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			booking_code varchar(10),
			booking_date varchar(10),
			booking_start_date varchar(10),
			booking_end_date varchar(10),
			no_of_adult int(10),
			no_of_child int(10),
			name varchar(200),
			mobile varchar(200),
			email varchar(200),
			address text,
			id_proof varchar(50),
			id_proof_number varchar(50),
			proof_file text,
			amount int(10),
			payment_id varchar(200),
			status varchar(20),
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {

		//public(frontend) files
		include_once ('public/common-functions.php');
		include_once ('public/class-rb-shortcodes.php');
		include_once ('public/class-rb-ajax.php');
		
		if( is_admin() ){
			include_once ('admin/class-settings-api.php');
			include_once ('admin/class-resort-settings.php');
			include_once ('admin/class-rb-admin-ajax.php');
		}
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0
	 */
	private function init_hooks() {

		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", array( $this, 'my_plugin_settings_link' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'resort_booking_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'resort_booking_enqueue_styles_admin' ) );
		add_action( 'admin_menu', array( $this, 'add_resort_booking_menu' ) );

	}

	public function my_plugin_settings_link($links) { 
	  	$settings_link = '<a href="options-general.php?page=resort_booking_settings">Settings</a>'; 
	  	array_unshift($links, $settings_link); 
	  	return $links; 
	}

	public function add_resort_booking_menu(){
		add_menu_page('Resort Booking', 'Resort Booking', 'manage_options', 'resort-booking', array( $this, 'resort_booking_list' ) );
	}

	public function resort_booking_list(){
		include_once ('admin/class-resort-booking-list.php');

		$Resort_Booking_List = new Resort_Booking_List();

		// Fetch, prepare, sort, and filter our data.
		$Resort_Booking_List->prepare_items();
		$Resort_Booking_List->display();
	}

	public function resort_booking_enqueue_styles() { 
	    wp_enqueue_script("jquery-ui-tabs");

	    // Load the datepicker script (pre-registered in WordPress).
	    wp_enqueue_script( 'jquery-ui-datepicker' );

	    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
	    wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
	    wp_enqueue_style( 'jquery-ui' );  

		//wp_enqueue_style( 'bootstrap-css', SB_PLUGIN_URL.'/assets/css/bootstrap.min.css', false, time() );
		wp_enqueue_style( 'resort-booking-css', RB_PLUGIN_URL.'/assets/css/resort-booking.css', false, time() );
		wp_enqueue_style( 'waitMe-min-css', RB_PLUGIN_URL.'/assets/css/waitMe.min.css', false, time() );

	    wp_enqueue_script( 'waitMe-min-js', RB_PLUGIN_URL.'/assets/js/waitMe.min.js', array( 'jquery' ), time() );
	    wp_enqueue_script( 'jquery-validate-min-js', RB_PLUGIN_URL.'/assets/js/jquery.validate.min.js', array( 'jquery' ), time() );
	    wp_enqueue_script( 'razorpay-checkout-js', 'https://checkout.razorpay.com/v1/checkout.js', array( 'jquery' ), time() );
	    
	    wp_register_script( 'resort-booking-js', RB_PLUGIN_URL.'/assets/js/resort-booking.js', array( 'jquery' ), time() );
		 
		// Localize the script with new data
		$resort_booking = array(
		    'ajaxurl' => admin_url('admin-ajax.php'),
		    'resort_booking_basic_settings' => get_option( 'resort_booking_basic_settings' ),
		    //'person_capacity' => apply_filters( 'safari_booking_total_person_capacity', 7 ),
		);
		wp_localize_script( 'resort-booking-js', 'resort_booking', $resort_booking );
		 
		// Enqueued script with localized data.
		wp_enqueue_script( 'resort-booking-js' );

	}

	public function resort_booking_enqueue_styles_admin() { 

		wp_enqueue_style( 'jquery-ui', RB_PLUGIN_URL.'/assets/css/jquery-ui.min.css', false, time() );
		//wp_enqueue_style( 'bootstrap-css', SB_PLUGIN_URL.'/assets/css/bootstrap.min.css', false, time() );
		wp_enqueue_style( 'resort-booking-admin-css', RB_PLUGIN_URL.'/assets/css/resort-booking-admin.css', false, time() );
		wp_enqueue_style( 'waitMe-min-css', RB_PLUGIN_URL.'/assets/css/waitMe.min.css', false, time() );

		wp_enqueue_script( 'jquery-ui-datepicker' );
	    wp_enqueue_script( 'waitMe-min-js', RB_PLUGIN_URL.'/assets/js/waitMe.min.js', array( 'jquery' ), time() );
	    
	    wp_enqueue_script( 'resort-booking-repeater-js', RB_PLUGIN_URL.'/assets/js/jquery.repeater.min.js', array( 'jquery' ), time() );
	    wp_register_script( 'resort-booking-admin-js', RB_PLUGIN_URL.'/assets/js/resort-booking-admin.js', array( 'jquery', 'jquery-ui-datepicker' ), time(), true );
		 
		// Localize the script with new data
		$resort_booking = array(
		    'ajaxurl' => admin_url('admin-ajax.php'),
		    'resort_booking_basic_settings' => get_option( 'resort_booking_basic_settings' )
		);
		wp_localize_script( 'resort-booking-admin-js', 'resort_booking', $resort_booking );
		 
		// Enqueued script with localized data.
		wp_enqueue_script( 'resort-booking-admin-js' );

	}

}
new Resort_Booking();