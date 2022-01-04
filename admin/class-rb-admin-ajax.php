<?php
/**
 * SB_Ajax
 */
class SB_Admin_Ajax{
	
	function __construct(){
		add_action( 'wp_ajax_get_booking_information', array( $this, 'get_booking_information' ) );
		add_action( 'wp_ajax_nopriv_get_booking_information', array( $this, 'get_booking_information' ) );
	}

	public function get_booking_information(){

		global $wpdb;

		$upload_dir = wp_upload_dir();

		$booking_id = $_POST['booking_id'];

		ob_start();
		
		?>
		<table cellspacing="0" cellpadding="0" width="100%" style="margin: 0px auto; font-family: sans-serif; font-size: 15px; max-width: 580px;">
			<tbody>
				<tr>
					<td>
						<table width="100%" style="background: #ffffff; padding: 15px; border: 1px solid #efefef; font-size: 13px;" >
							<tbody>
								<?php

								$resort_booking_main_table = $wpdb->prefix . 'resort_booking';

								$booking_person = $wpdb->get_row( "
									SELECT * FROM
										$resort_booking_main_table
									WHERE
										id = '".$booking_id."'
								", ARRAY_A );

								if( !empty( $booking_person ) ){
									$proof_file = $upload_dir['baseurl'].'/resort_booking/'.$booking_id.'/'.$booking_person['proof_file']; ?>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Name:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['name']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Mobile:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['mobile']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Email:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['email']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Address:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['address']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Check In Date:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['booking_start_date']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Check Out Date:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['booking_end_date']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Adults:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['no_of_adult']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Child:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['no_of_child']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['id_proof']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">ID Proof Number:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['id_proof_number']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Status:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['status']; ?></span></td>
										</tr>
										<tr>
											<td height=25><label style="font-weight: bold; padding-right: 15px;">Amount:</label><span></span></td>
											<td height=25><label style="font-weight: bold; padding-right: 15px;"></label><span><?php echo $booking_person['amount']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2"><label style="font-weight: bold; padding-right: 15px;">ID Proof Photo:</label><a href="<?php echo $proof_file; ?>" target="_blank"><img src="<?php echo $proof_file; ?>" style="width: 100px;display: block;"></a></td>
										</tr>
										<?php
								}

								?>

							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php
		
		$html = ob_get_clean();

		wp_send_json_success(array(
			'html' => $html
		));		

	}

}

new SB_Admin_Ajax();

?>