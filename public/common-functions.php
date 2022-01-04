<?php

function calculate_price( $adult = 0, $child = 0, $nationality = 'indian' ){

	$safari_booking_basic_settings = get_option( 'safari_booking_basic_settings' );
	$price = 0;

	if( $nationality == 'indian' ){
		$adult_price = $safari_booking_basic_settings['adult_price_indian'];
		$price = (int) $adult_price + ( (int) $safari_booking_basic_settings['child_price'] * (int) $child );
	}else{
		$price = $safari_booking_basic_settings['adult_price_foreigner'];
	}

	return $price;

}

add_action( 'wp_head', 'paymoney_header_data' );

add_action( 'init', function(){
	if( isset( $_REQUEST['stack'] ) && $_REQUEST['stack'] == 'true' ){

		$logo = get_theme_mod( 'custom_logo' );
		$image = wp_get_attachment_image_src( $logo , 'full' );
		echo $image_url = $image[0];
		echo $logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && ! empty( $user_logo ) ? $user_logo : $template_directory_uri . '/images/logo.png';
		$data = array(
			'email' => 'test_123@gmail.com',
			'booking_code' => 'FNXHALPSY6',
		);
		do_action( 'payumoney_booking_success', $data );
		die;
	}
} );

function paymoney_header_data(){
	global $post;
	$safari_booking_basic_settings = get_option( 'resort_booking_basic_settings' );
	//if( $post->ID == $safari_booking_basic_settings['payment_page'] ){
		if( $safari_booking_basic_settings['payumoney_mode'] == 'sandbox' ) { ?>
			<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo=""></script>
		<?php } else { ?>
			<script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo=""></script>
		<?php
		}
	//}
}

add_action( 'init', 'payumoney_response_handler' );

function payumoney_response_handler(){
	global $wpdb;
	$safari_booking_basic_settings = get_option( 'safari_booking_basic_settings' );
	if( isset( $_REQUEST['booking_code'] ) && $_REQUEST['booking_code'] != '' ){
		$postdata = $_POST;
		//echo '<pre>'; print_r( $postdata ); echo '</pre>';
		error_log( print_r($postdata, TRUE) );
		if ( isset( $postdata['key'] ) ) {
			$key				=   $safari_booking_basic_settings['payumoney_key'];//$postdata['key'];
			$salt				=   $safari_booking_basic_settings['payumoney_salt'];//'lU2OxdBuJn';//$postdata['salt'];
			$txnid 				= 	$postdata['txnid'];
		    $amount      		= 	$postdata['amount'];
			$productInfo  		= 	$postdata['productinfo'];
			$firstname    		= 	$postdata['firstname'];
			$email        		=	$postdata['email'];
			$udf5				=   $postdata['udf5'];
			$mihpayid			=	$postdata['mihpayid'];
			$payutxnid			= 	$postdata['payuMoneyId'];
			$status				= 	$postdata['status'];
			$resphash			= 	$postdata['hash'];
			//Calculate response hash to verify	
			$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
			$keyArray 	  		= 	explode("|",$keyString);
			$reverseKeyArray 	= 	array_reverse($keyArray);
			$reverseKeyString	=	implode("|",$reverseKeyArray);
			$CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString));
			
			
			if ( $status == 'success'  && $resphash == $CalcHashString ) {
				$msg = "Transaction Successful and Hash Verified...";
				//Do success order processing here...
				$booking_code = $_REQUEST['booking_code'];
				$safari_booking_table = $wpdb->prefix . 'safari_booking';
				$booking = $wpdb->get_row( "SELECT * FROM $safari_booking_table WHERE booking_code = '".$booking_code."' AND status = 'pending'", ARRAY_A );
				if( !empty( $booking ) ){
					$wpdb->update( $safari_booking_table, array( 'status' => 'success', 'payment_id' => $payutxnid ), array( 'booking_code' => $booking_code ) );
					$data = array(
						'email' => $booking['email'],
						'booking_code' => $booking_code,
					);
					do_action( 'payumoney_booking_success', $data );
					error_log('Booking Success');
				}
			} else {
				error_log('Hash is not matched. Booking cosider as fail.');
			}
			/*else {
				//tampered or failed
				$msg = "Payment failed for Hasn not verified...";
			}*/ 
		}

		error_log('**************************************'.$booking_code.'**************************************');
		error_log('Booking End');
	}
}

?>