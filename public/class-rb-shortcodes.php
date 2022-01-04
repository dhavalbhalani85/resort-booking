<?php
/**
 * SB_Shortcodes
 */
class SB_Shortcodes{
	
	function __construct(){
		/*add_shortcode( 'booking_form', array( $this, 'booking_form' ) );
		add_shortcode( 'booking_form_gir_jungle', array( $this, 'booking_form_gir_jungle' ) );
		add_shortcode( 'booking_form_devalia_park', array( $this, 'booking_form_devalia_park' ) );*/
		add_shortcode( 'resort_booking_form', array( $this, 'resort_booking_form' ) );
		//add_shortcode( 'booking_thank_you', array( $this, 'booking_thank_you' ) );
	}

	public function resort_booking_form( $atts ) {
		$resort_booking_basic_settings = get_option( 'resort_booking_basic_settings' );
		$thank_you_page_url = get_permalink( $resort_booking_basic_settings['thank_you_page'] );
	    $atts = shortcode_atts( array(
	        'thankyou_url' => '',
	    ), $atts, 'payment_form' );
	 	
	    ob_start(); ?>
	    <div class="booking">
		    <div class="container">
		        <h1>Resort Booking</h1>
		        <div class="row">
		            <input type="hidden" id="submit-form-click" value="0">
		            <form method="post" id="payment_form">
		                <div class="col-sm-12">
		                    <div class="panel booking">
		                        <div class="panel-heading booking-title">Booking Details</div>
		                        <div class="panel-body">
		                        	<input type="hidden" name="price" value="<?php echo $resort_booking_basic_settings['resort_price'] ?>">
		                        	<input type="hidden" name="total_amount" class="total_amount" value="15000">
		                            <div class="row">
		                                <div class="col-sm-6 col-xs-12">
		                                	<label for="bookingtime">Check in : </label>
		                                    <input type="text" class="form-control" name="checkin" id="start-datepicker" value="<?php echo date('d-m-Y'); ?>" required="">
		                                </div>
		                                <div class="col-sm-6 col-xs-12 checkout-date">
		                                    <label for="bookingtime">Check out : </label>
		                                     <input type="text" class="form-control" name="checkout" id="end-datepicker" value="<?php echo date('d-m-Y'); ?>" required="">
		                                </div>
		                            </div>
		                            <div class="row">
		                                
		                                <div class="col-sm-6 col-xs-12">
		                                    <label for="bookingtime"> Adult :</label>
		                                    <input type="number" class="form-control" name="adults" id="adults" value="" required="">
		                                </div>
		                                <div class="col-sm-6 col-xs-12 children">
		                                    <label for="bookingtime"> Children :</label>
		                                    <input type="number" class="form-control" name="children" id="children" value="" required="">
		                                </div>
		                            </div>
		                            <div class="row">
		                                <div class="col-sm-6 col-xs-12">
		                                	<label for="bookingtime">Name :</label>
		                                    <input type="text" class="form-control" name="name" id="name" value="" required="">
		                                </div>
		                                <div class="col-sm-6 col-xs-12 email">
		                                    <label for="bookingtime">Email :</label>
		                                    <input type="text" class="form-control" name="email" id="email" value="" required="">
		                                </div>
		                            </div>

		                            <div class="row">
		                                <div class="col-sm-6 col-xs-12">
		                                    <label for="bookingtime">Phone</label>
		                                    <input type="number" class="form-control" name="phone" id="phone" value="" required="">
		                                </div>
		                                <div class="col-sm-6 col-xs-12 address">
	                                        <label for="mobile">Full Address : </label>
	                                        <textarea class="form-control" rows="3" name="address" placeholder="Full Address..." required=""></textarea>
	                                    </div>
		                            </div>


		                            <div class="row">
		                                <div class="col-sm-6 col-xs-12">
		                                	<label for="mobile">Select Document : </label>
	                                        <select class="form-control proof_select" name="doc_type" required="">
	                                            <option value="">ID Proof</option>
	                                            <option value="Aadhar Card">Aadhar Card</option>
	                                            <option value="Voter ID">Voter ID</option>
	                                            <option value="Driving Licence">Driving Licence</option>
	                                            <option value="Passport">Passport</option>
	                                        </select>
		                                </div>
		                                <div class="col-sm-6 col-xs-12 id-proof">
		                                    <label for="mobile">Id Proof Number : </label>
											<input type="text" class="form-control" name="id_proof_number" id="id_proof_number" placeholder="Id Proof Number" required="">
		                                </div>
		                            </div>
		                            <div class="row">
		                            	<div class="form-group adult-id-file upload">
		                            		 <label for="mobile">Upload Document : </label>
                                            <input type="file" title="This field is required. and the image Format Must Be JPG, JPEG, PNG and Maximum File Size Limit is 3MB." class="form-control idprooffile" required="required" accept=".png, .jpg, .jpeg" name="document">
                                            <div id="Div1">Upload (.jpg/.jpeg/.png) only. <br>size should be less then 8 MB </div>
                                            <div class="alert" id="message" style="display: none"></div>
                                        </div>
		                            </div>
		                            <div class="payment-button">
		                            	<?php wp_nonce_field(); ?>
		                            	<a href="javascript:void(0);" onclick="return false;" class="btn btn-warning total-amount">Payable amount : <span id="lblTotal">₹15000</span>
		                                                </a>
		                                 <input type="hidden" name="thankyou_url" value="<?php echo $thank_you_page_url; ?>" id="thankyou_url">                
                                    </div>
                                    <div class="book-resort-btn">
                                       <button type="submit" id="book-now" class="btn btn-success book-now" style="cursor: pointer;">Book Now</button>
                                    </div>
		                        </div>
		                    </div>
		                </div>
		            </form>
		        </div>
		    </div>
		</div>
	    <?php
	    $html = ob_get_clean();
	    return $html;
	}
	public function booking_thank_you( $atts ){

		global $wpdb;

		$upload_dir = wp_upload_dir();

		$atts = shortcode_atts( array(
	        '' => '',
	    ), $atts, 'booking_thank_you' );
	 	
		$html = '';

	    if( isset( $_GET['booking_code'] ) && $_GET['booking_code'] != '' ){
			
			$safari_booking_table = $wpdb->prefix . 'safari_booking';
			
			$booking = $wpdb->get_row( "
				SELECT * FROM 
					$safari_booking_table 
				WHERE 
					booking_code = '".$_GET['booking_code']."' 
			",ARRAY_A );
			
			if( !empty( $booking ) ){

			    ob_start(); 

				?>
				<table class="thank-you" cellspacing="0" cellpadding="0" width="100%" style="margin: 0px auto; font-family: sans-serif; font-size: 15px; max-width: 580px;">
					<tbody>
						<!-- <tr>
							<td cellspacing="0" align="center"><a href="#" ><img src="logo.png" style="margin-bottom: -3px;" ></a></td>
						</tr> -->
						<tr>
							<td style="padding: 0; border: none!important; ">
								<table align="center" cellspacing="0" width="100%" style="background: #E09900; padding: 15px; border-radius: 15px 15px 0px 0px; margin: 0; border: none!important;" >
									<thead>
										<tr>
											<td align="center" style="color:#ffffff; font-size: 18px; " >Booking Details</td>
										</tr>
									</thead>
								</table>
								
							</td>
						</tr>
						<tr>
							<td style="padding: 0; ">
								<table width="100%" style="background: #ffffff; padding: 15px; border: 1px solid #efefef; font-size: 13px;" >
									<tbody>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Booking Code</label></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"><?php echo $booking['booking_code']; ?></label></td>
										</tr>
										<!-- <tr>
											<td colspan="2" style="padding:0px;" ><hr style="border: 1px solid #efefef!important; border-width: 1px 0 0 0!important;padding-top: 0px; margin-top: 0px;"></td>
										</tr> -->
										<tr>
											<td height="25"><label style="font-weight: bold; padding-right: 15px;">Booking Date:</label> <value><?php echo date( 'd-m-Y', strtotime( $booking['booking_date'] ) ); ?></value></td>
											<td height="25"><label style="font-weight: bold; padding-right: 15px;">Booking Timing:</label> <value><?php echo $booking['booking_time']; ?></value></td>
										</tr>
										<tr>
											<td height="25"><label style="font-weight: bold; padding-right: 15px;">No. of Adult:</label> <value><?php echo $booking['no_of_adult']; ?></value></td>
											<td height="25"><label style="font-weight: bold; padding-right: 15px;">No. of Child:</label> <value><?php echo $booking['no_of_child']; ?></value></td>
										</tr>
										<tr>
											<td colspan="2" height="25"><label style="font-weight: bold; padding-right: 15px;">Name:</label> <value><?php echo $booking['name']; ?></value></td>
										</tr>
										<tr>
											<td colspan="2" height="25"><label style="font-weight: bold; padding-right: 15px;">Email:</label> <value><?php echo $booking['email']; ?></value></td>
										</tr>
										<tr>
											<td colspan="2" height="25"><label style="font-weight: bold; padding-right: 15px;">Mobile Number:</label> <value><?php echo $booking['mobile']; ?></value></td>
										</tr>
										<tr>
											<td colspan="2" height="25"><label style="font-weight: bold; padding-right: 15px;">Full Address:</label> <value><?php echo $booking['address']; ?></value></td>
										</tr>
										<!-- <tr>
											<td colspan="2" style="padding:0px;" ><hr style="border: 1px solid #efefef!important; border-width: 1px 0 0 0!important;padding-top: 0px; margin-top: 0px;"></td>
										</tr> -->
										
										<?php

										$safari_booking_customers_table = $wpdb->prefix . 'safari_booking_customers';
			
										$booking_customers = $wpdb->get_results( "
											SELECT * FROM 
												$safari_booking_customers_table 
											WHERE 
												booking_id = '".$booking['id']."'
											AND 
												person_type = 'adult' 
										",ARRAY_A );

										if( !empty( $booking_customers ) ){

											$i = 1;

											foreach ( $booking_customers as $key => $booking_customers_adult ) { 
												$proof_file = $upload_dir['baseurl'].'/safari_booking/'.$booking['id'].'/'.$booking_customers_adult['id'].'/'.$booking_customers_adult['proof_file'];
												?>

											<tr>
												<td colspan="2" style="padding-bottom: 20px;" ><strong>Adult Details</strong></td>
											</tr>
											<tr>
												<td height="25"><span style="font-weight: bold; padding-right: 15px;"><?php echo $i; ?></span><span>Adult</span></td>
												<td height="25"><label style="font-weight: bold padding-right: 15px;"><?php echo $booking_customers_adult['name']; ?></label><span></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Age:</label><span><?php echo $booking_customers_adult['age']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Gender:</label><span><?php echo $booking_customers_adult['gender']; ?></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Nationality:</label><span><?php echo $booking_customers_adult['nationality']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Select State:</label><span><?php echo $booking_customers_adult['state']; ?></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof:</label><span><?php echo $booking_customers_adult['id_proof']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof Number:</label><span><?php echo $booking_customers_adult['id_proof_number']; ?></span></td>
											</tr>
											<tr>
												<td colspan="2"><label style="font-weight: bold; padding-right: 15px;">ID Proof Photo:</label><img src="<?php echo $proof_file; ?>" style="width: 100px;display: block;"></td>
											</tr>

										<?php $i++; } } ?>

										<!-- <tr>
											<td colspan="2" style="padding:0px;"><hr style="border: 1px solid #efefef!important; border-width: 1px 0 0 0!important;padding-top: 0px; margin-top: 0px;"></td>
										</tr> -->

										<?php

										$safari_booking_customers_table = $wpdb->prefix . 'safari_booking_customers';
			
										$booking_customers = $wpdb->get_results( "
											SELECT * FROM 
												$safari_booking_customers_table 
											WHERE 
												booking_id = '".$booking['id']."'
											AND 
												person_type = 'child' 
										",ARRAY_A );

										if( !empty( $booking_customers ) ){

											$i = 1;

											foreach ( $booking_customers as $key => $booking_customers_child ) { ?>

											<tr>
												<td colspan="2" style="padding-bottom: 20px;" ><strong>Child Details</strong></td>
											</tr>
											<tr>
												<td height="25"><span style="font-weight: bold; padding-right: 15px;"><?php echo $i; ?></span><span>Child</span></td>
												<td height="25"><label style="font-weight: bold padding-right: 15px;"><?php echo $booking_customers_child['name']; ?></label><span></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Age:</label><span><?php echo $booking_customers_child['age']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Gender:</label><span><?php echo $booking_customers_child['gender']; ?></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Nationality:</label><span><?php echo $booking_customers_child['nationality']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">Select State:</label><span><?php echo $booking_customers_child['state']; ?></span></td>
											</tr>
											<tr>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof:</label><span><?php echo $booking_customers_child['id_proof']; ?></span></td>
												<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof Number:</label><span><?php echo $booking_customers_child['id_proof_number']; ?></span></td>
											</tr>

										<?php $i++; } } ?>

										<tr>
											<td colspan="2" style="padding:0px;"><hr style="border: 1px solid #efefef!important; border-width: 1px 0 0 0!important;padding-top: 0px; margin-top: 0px;"></td>
										</tr>

										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Total:</label></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">₹<?php echo $booking['amount']; ?></label></td>
										</tr>

									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<?php

				$html = ob_get_clean();

			}
	    }  

	    return $html;	
	}
}
new SB_Shortcodes();	
?>