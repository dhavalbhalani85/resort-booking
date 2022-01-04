(function($){
	$(document).ready(function(){
		$("#start-datepicker, #end-datepicker").datepicker({
			dateFormat: 'dd-mm-yy',
			minDate: 0 ,
			onSelect: function(dateStr) {
		      var d1 = $('#start-datepicker').datepicker('getDate');
		      var d2 = $('#end-datepicker').datepicker('getDate');
		      var diff = 0;
		      if (d1 && d2) {
		            diff = Math.floor((d2.getTime() - d1.getTime()) / 86400000); // ms per day
		      }
		      diff = diff + 1;
		      if( diff > 0  ){
		      		$('#lblTotal').text(diff * 15000);
		      		$('.total_amount').val(diff * 15000);
		      }else{
		      	alert("Please check checkin checkout date");
		      }
		      
		}
		});

		var payment_form  = $("#payment_form").validate();

		$(document).on('click', '#book-now', function (e) {

			e.preventDefault();

			var $this = $(this);

			if($( "#payment_form" ).valid()){

				$this.closest('#payment_form').waitMe({
					effect : 'bounce',
					text : '',
					bg : 'rgba(255,255,255,0.7)',
					color : '#000000',
					maxSize : '',
					waitTime : -1,
					textPos : 'vertical',
					fontSize : '',
					source : '',
					onClose : function() {}
				});

			  	var form = $('#payment_form')[0];
		    	var formData = new FormData(form);
		    		formData.append('action','book_now_resort');
		    		formData.append('form_total',$('#lblTotal').text());

			  	$.ajax({
		          	type : "POST",
		          	dataType : "json",
		          	url : resort_booking.ajaxurl,
		          	data : formData,
		          	processData: false,
		          	contentType: false,
		          	success: function(response) {
		          		
		            	if( response.success ){
		            		console.log( response );
		            		var form = $('#payment_form')[0];
					    	var formData = new FormData(form);
					    		formData.append('action','add_payumoney_resort_booking');
					    		
		            		$.ajax({
		            			url:resort_booking.ajaxurl,
				                type: 'post',
				                data : formData,
				                dataType: 'json',
				                processData: false,
      							contentType: false,
				                success: function (response) {
				                	console.log( response );
				                	if( response.success ) {
				                		bolt.launch({
											key: resort_booking.resort_booking_basic_settings.payumoney_key,//$('#key').val(),
											txnid: response.data.booking_code, //$('#txnid').val(), 
											hash: response.data.hash, //$('#hash').val(),
											amount: response.data.data.total_amount, //$('#amount').val(),
											firstname: response.data.data.name, //$('#fname').val(),
											email: response.data.data.email, //$('#email').val(),
											phone: response.data.data.phone, //$('#mobile').val(),
											productinfo: 'Resort Booking', //$('#pinfo').val(),
											udf5: 'BOLT_KIT_PHP7', //$('#udf5').val(),
											surl : response.data.redirect, //$('#surl').val(),
											furl: response.data.redirect, //$('#surl').val(),
											mode: 'dropout'	
										},{ responseHandler: function( BOLT ) {
											console.log( BOLT.response.txnStatus );
											
											if( BOLT.response.txnStatus != 'CANCEL' ) {
												//Salt is passd here for demo purpose only. For practical use keep salt at server side only.
												var fr = '<form action=\"'+response.data.redirect+'\" method=\"post\">' +
												'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
												'<input type=\"hidden\" name=\"salt\" value=\"'+safari_booking.safari_booking_basic_settings.payumoney_salt+'\" />' +
												'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
												'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
												'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
												'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
												'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
												'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
												'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
												'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
												'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
												'</form>';
												var form = jQuery(fr);
												jQuery('body').append(form);								
												form.submit();
											}
										}, catchException: function(BOLT){
												alert( BOLT.message );
											}
										});
				                	} else {
				                		alert( response.data.message );
				                	}
				                },
				                error: function (jqXHR, exception) {
					          		var msg = '';
							        if (jqXHR.status === 0) {
							            msg = 'Not connect.\n Verify Network.';
							        } else if (jqXHR.status == 404) {
							            msg = 'Requested page not found. [404]';
							        } else if (jqXHR.status == 500) {
							            msg = 'Internal Server Error [500].';
							        } else if (exception === 'parsererror') {
							            msg = 'Requested JSON parse failed.';
							        } else if (exception === 'timeout') {
							            msg = 'Time out error.';
							        } else if (exception === 'abort') {
							            msg = 'Ajax request aborted.';
							        } else {
							            msg = 'Uncaught Error.\n' + jqXHR.responseText;
							        }
							        console.log(msg);
							        $this.closest('#payment_form').waitMe('hide');
					          	}
		            		});
		            	}else{
		            		alert(response.data.message);
		            	}

		            	$this.closest('#payment_form').waitMe('hide');

		          	},error: function (jqXHR, exception) {
		          		var msg = '';
				        if (jqXHR.status === 0) {
				            msg = 'Not connect.\n Verify Network.';
				        } else if (jqXHR.status == 404) {
				            msg = 'Requested page not found. [404]';
				        } else if (jqXHR.status == 500) {
				            msg = 'Internal Server Error [500].';
				        } else if (exception === 'parsererror') {
				            msg = 'Requested JSON parse failed.';
				        } else if (exception === 'timeout') {
				            msg = 'Time out error.';
				        } else if (exception === 'abort') {
				            msg = 'Ajax request aborted.';
				        } else {
				            msg = 'Uncaught Error.\n' + jqXHR.responseText;
				        }
				        //console.log(msg);
				        $this.closest('#payment_form').waitMe('hide');
		          	}
		      	});

		    }else{
			 	payment_form.focusInvalid();
			}
		            
		});

		

		function calculate_price( adult = 0, child = 0, nationality = 'indian' ){

			var resort_booking_basic_settings = resort_booking.resort_booking_basic_settings;
			var price = 0;

			if( nationality == 'indian' ){
				var adult_price = resort_booking_basic_settings.adult_price_indian;
				price = parseInt( adult_price ) + ( parseInt( resort_booking_basic_settings.child_price ) * parseInt( child ) );
			}else{
				price = resort_booking_basic_settings.adult_price_foreigner;
			}

			return price;

		}

		$(document).on('click', '#pay-now', function (e) {

			e.preventDefault();

			var $this = $(this);

			if($( "#payment_form" ).valid()){

				$this.closest('#payment_form').waitMe({
					effect : 'bounce',
					text : '',
					bg : 'rgba(255,255,255,0.7)',
					color : '#000000',
					maxSize : '',
					waitTime : -1,
					textPos : 'vertical',
					fontSize : '',
					source : '',
					onClose : function() {}
				});

			  	var form = $('#payment_form')[0];
		    	var formData = new FormData(form);
		    		formData.append('action','check_availability_and_verify');

			  	$.ajax({
		          	type : "POST",
		          	dataType : "json",
		          	url : safari_booking.ajaxurl,
		          	data : formData,
		          	processData: false,
		          	contentType: false,
		          	success: function(response) {
		          		
		            	if( response.success ){
		            		console.log( response );
		            		var form = $('#payment_form')[0];
					    	var formData = new FormData(form);
					    		formData.append('action','add_payumoney_resort_booking');
					    		formData.append( 'total_amount', response.data.total_amount );

		            		$.ajax({
		            			url:safari_booking.ajaxurl,
				                type: 'post',
				                data : formData,
				                dataType: 'json',
				                processData: false,
      							contentType: false,
				                success: function (response) {
				                	console.log( response );
				                	if( response.success ) {
				                		bolt.launch({
											key: safari_booking.safari_booking_basic_settings.payumoney_key,//$('#key').val(),
											txnid: response.data.booking_code, //$('#txnid').val(), 
											hash: response.data.hash, //$('#hash').val(),
											amount: response.data.data.total_amount, //$('#amount').val(),
											firstname: response.data.data.customer_name, //$('#fname').val(),
											email: response.data.data.email, //$('#email').val(),
											phone: response.data.data.mobile, //$('#mobile').val(),
											productinfo: 'Gir Lion Safari Booking', //$('#pinfo').val(),
											udf5: 'BOLT_KIT_PHP7', //$('#udf5').val(),
											surl : response.data.redirect, //$('#surl').val(),
											furl: response.data.redirect, //$('#surl').val(),
											mode: 'dropout'	
										},{ responseHandler: function( BOLT ) {
											console.log( BOLT.response.txnStatus );
											
											if( BOLT.response.txnStatus != 'CANCEL' ) {
												//Salt is passd here for demo purpose only. For practical use keep salt at server side only.
												var fr = '<form action=\"'+response.data.redirect+'\" method=\"post\">' +
												'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
												'<input type=\"hidden\" name=\"salt\" value=\"'+safari_booking.safari_booking_basic_settings.payumoney_salt+'\" />' +
												'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
												'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
												'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
												'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
												'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
												'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
												'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
												'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
												'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
												'</form>';
												var form = jQuery(fr);
												jQuery('body').append(form);								
												form.submit();
											}
										}, catchException: function(BOLT){
												alert( BOLT.message );
											}
										});
				                	} else {
				                		alert( response.data.message );
				                	}
				                },
				                error: function (jqXHR, exception) {
					          		var msg = '';
							        if (jqXHR.status === 0) {
							            msg = 'Not connect.\n Verify Network.';
							        } else if (jqXHR.status == 404) {
							            msg = 'Requested page not found. [404]';
							        } else if (jqXHR.status == 500) {
							            msg = 'Internal Server Error [500].';
							        } else if (exception === 'parsererror') {
							            msg = 'Requested JSON parse failed.';
							        } else if (exception === 'timeout') {
							            msg = 'Time out error.';
							        } else if (exception === 'abort') {
							            msg = 'Ajax request aborted.';
							        } else {
							            msg = 'Uncaught Error.\n' + jqXHR.responseText;
							        }
							        console.log(msg);
							        $this.closest('#payment_form').waitMe('hide');
					          	}
		            		});

		              		/*var razorpay_options = {
						        key: safari_booking.safari_booking_basic_settings.razor_pay_key_id,
						        amount: response.data.total_amount * 100,
						        name: 'Gir Lion Safari Booking',
						        description: 'Gir Lion Safari Booking',
						        image: 'http://girlionsafaribooking.com/wp-content/uploads/2021/01/logo.png',
						        netbanking: true,
						        currency: 'INR',
						        prefill: {
						            name: $('input[name="customer_name"]').val(),
						            email: $('input[name="email"]').val(),
						            contact: $('input[name="mobile"]').val()
						        },
						        handler: function (transaction) {
						        	
						        	var form = $('#payment_form')[0];
							    	var formData = new FormData(form);
							    		formData.append('action','add_safari_booking');
							    		formData.append('razorpay_payment_id',transaction.razorpay_payment_id);
							    		formData.append('total_amount',response.data.total_amount);

							    		$this.closest('#payment_form').waitMe({
											effect : 'bounce',
											text : '',
											bg : 'rgba(255,255,255,0.7)',
											color : '#000000',
											maxSize : '',
											waitTime : -1,
											textPos : 'vertical',
											fontSize : '',
											source : '',
											onClose : function() {}
										});


							            $.ajax({
							                url:safari_booking.ajaxurl,
							                type: 'post',
							                data : formData,
							                dataType: 'json',
							                processData: false,
		          							contentType: false,
							                success: function (response) {
							                    if(response.success){
							                        window.location = response.data.redirect;
							                    }else{
							                    	alert( response.data.message );
							                    }
							                    $this.closest('#payment_form').waitMe('hide');
							                },error: function (jqXHR, exception) {
								          		var msg = '';
										        if (jqXHR.status === 0) {
										            msg = 'Not connect.\n Verify Network.';
										        } else if (jqXHR.status == 404) {
										            msg = 'Requested page not found. [404]';
										        } else if (jqXHR.status == 500) {
										            msg = 'Internal Server Error [500].';
										        } else if (exception === 'parsererror') {
										            msg = 'Requested JSON parse failed.';
										        } else if (exception === 'timeout') {
										            msg = 'Time out error.';
										        } else if (exception === 'abort') {
										            msg = 'Ajax request aborted.';
										        } else {
										            msg = 'Uncaught Error.\n' + jqXHR.responseText;
										        }
										        console.log(msg);
										        $this.closest('#payment_form').waitMe('hide');
								          	}
							            });
						        },
						        "modal": {
						            "ondismiss": function () {
						                console.log('ondismiss called');
						            }
						        },
						        "theme": {
									"color": "#f58220"
								}
						    };
						    // obj        
						    var objrzpv1 = new Razorpay(razorpay_options);
						    objrzpv1.open();*/
		            	}else{
		            		alert(response.data.message);
		            	}

		            	$this.closest('#payment_form').waitMe('hide');

		          	},error: function (jqXHR, exception) {
		          		var msg = '';
				        if (jqXHR.status === 0) {
				            msg = 'Not connect.\n Verify Network.';
				        } else if (jqXHR.status == 404) {
				            msg = 'Requested page not found. [404]';
				        } else if (jqXHR.status == 500) {
				            msg = 'Internal Server Error [500].';
				        } else if (exception === 'parsererror') {
				            msg = 'Requested JSON parse failed.';
				        } else if (exception === 'timeout') {
				            msg = 'Time out error.';
				        } else if (exception === 'abort') {
				            msg = 'Ajax request aborted.';
				        } else {
				            msg = 'Uncaught Error.\n' + jqXHR.responseText;
				        }
				        console.log(msg);
				        $this.closest('#payment_form').waitMe('hide');
		          	}
		      	});

		    }else{
			 	payment_form.focusInvalid();
			}
		            
		});
	});

})(jQuery);