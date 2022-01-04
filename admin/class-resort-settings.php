<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('WeDevs_Settings_API_Test' ) ):
class WeDevs_Settings_API_Test {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'Resort Booking Settings', 'Resort Booking Settings', 'delete_posts', 'resort_booking_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'resort_booking_basic_settings',
                'title' => __( 'Basic Settings', 'wedevs' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'resort_booking_basic_settings' => array(
                array(
                    'name'    => 'payumoney_mode',
                    'label'   => __( 'PayUmoney Mode', 'wedevs' ),
                    'desc'    => __( '', 'wedevs' ),
                    'type'    => 'select',
                    'default' => 'sandbox',
                    'options' => array(
                        'sandbox' => 'Sandbox',
                        'production'  => 'Production'
                    )
                ),
                array(
                    'name'              => 'payumoney_key',
                    'label'             => __( 'PayUmoney Key', 'wedevs' ),
                    'desc'              => __( '', 'wedevs' ),
                    'placeholder'       => __( 'PayUmoney key', 'wedevs' ),
                    'type'              => 'text',
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'payumoney_salt',
                    'label'             => __( 'PayUmoney Salt', 'wedevs' ),
                    'desc'              => __( '', 'wedevs' ),
                    'placeholder'       => __( 'PayUmoney salt', 'wedevs' ),
                    'type'              => 'text',
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'resort_price',
                    'label'             => __( 'Resort Price', 'wedevs' ),
                    'desc'              => __( 'Fixed price for resort', 'wedevs' ),
                    'placeholder'       => __( '0', 'wedevs' ),
                    'min'               => 0,
                    'max'               => 1000000,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'    => 'thank_you_page',
                    'label'   => __( 'Select thank you Page', 'wedevs' ),
                    'desc'    => __( 'Select the thank you page and put this <strong>[thank_you_form]</strong> shortcode anywhere you want on that page.', 'wedevs' ),
                    'type'    => 'pages',
                ),
                array(
                    'name'              => 'admin_email',
                    'label'             => __( 'Admin email for recieve booking email', 'wedevs' ),
                    'desc'              => __( '', 'wedevs' ),
                    'placeholder'       => __( 'Admin email address', 'wedevs' ),
                    'type'              => 'text',
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'    => 'setup',
                    'label'   => __( 'Setup', 'wedevs' ),
                    'desc'        => __( '
                        <strong>This shortcode displayed below are available in this plugin to user proper functionality of resort booking process.</strong>
                        <div style="margin: 10px 0;"><code>[resort_booking_form]</code> This shorcode will display basic booking fields.</div>
                        <div style="margin: 10px 0;"><code>[booking_thank_you]</code> This shorcode will display booking information after customer booking.</div>
                    ', 'wedevs' ),
                    'type'        => 'html'
                ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';

        
        //print('<pre>'.print_r( $_POST, true ).'</pre>');
        if( isset( $_POST['submit_resort_disable_date'] ) ){
        	$submit_data = json_encode( $_POST );
        	$submit_data_arr = json_decode( $submit_data, true );
        	update_option( 'resort_disbale_dates', $submit_data_arr['group-a'] );
        	//print('<pre>'.print_r( $submit_data_arr, true ).'</pre>');
        }
        ?>
        <div class="wrap">
            <div class="metabox-holder">
                <div id="resort_booking_disable_dates" class="resort_booking_disable_dates">
                	<h3>Disable Dates for Resort Booking</h3>
                	<form method="post" class="repeater">
                		<div data-repeater-list="group-a">
	                		<?php
	                		
	                		$resort_disbale_dates = get_option( 'resort_disbale_dates', true );
	                		if( is_array( $resort_disbale_dates ) && !empty( $resort_disbale_dates ) ){
	                			foreach ( $resort_disbale_dates as $key => $date_record ) {
	                				//print('<pre>'.print_r( $date_record, true ).'</pre>');
	                				?>
	                				<div class="card" data-repeater-item>
										<div class="fields">
											<div class="form-group">
												<label class="control-label">Disable Date</label>
												<input type="text" class="datepicker form-control" name="disbale_date" autocomplete="off" value="<?php echo $date_record['disbale_date'] ?>" />
											</div>
											<input data-repeater-delete type="button" class="button button-primary" value="Delete"/>
										</div>
									</div>
									<?php
	                			}
	                		} else {
		                		?>
								<div class="card" data-repeater-item>
									<div class="fields">
										<div class="form-group">
											<label class="control-label">Disable Date</label>
											<input type="text" class="datepicker form-control" name="disbale_date" autocomplete="off" />
										</div>
										<input data-repeater-delete type="button" class="button button-primary" value="Delete"/>
									</div>
								</div>
							<?php } ?>
						</div>
						<input data-repeater-create type="button" class="button button-primary" value="Add Dates"/>
						<input type="submit" name="submit_resort_disable_date" class="button button-primary" value="Submit Resort Dates">
                	</form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

new WeDevs_Settings_API_Test();