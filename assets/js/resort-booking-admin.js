(function( $ ){

	$(document).on('click','#view-more-detail',function(){

		$('#BookingDetailModal').show();
		$('.booking-detail').html('');

		$this = $(this);

		$('#BookingDetailModal .modal-content').waitMe({
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
          	type : "POST",
          	dataType : "json",
          	url : resort_booking.ajaxurl,
          	data : {action:'get_booking_information',booking_id:$(this).data('booking-id')},
          	success: function(response) {
            	if( response.success ){
            		$('.booking-detail').html( response.data.html ).show();
            	}else{
            		alert( response.data.message );
            	}
            	$('#BookingDetailModal .modal-content').waitMe('hide');
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
		        $('#BookingDetailModal .modal-content').waitMe('hide');
          	}
      	});

	});

	$(document).on('click','#BookingDetailModal .close',function(){
		$('#BookingDetailModal').hide();
	});

	$( '.repeater' ).repeater();
	$( '.repeater_devalia' ).repeater();
	$( '.datepicker' ).datepicker({
		dateFormat : "dd-mm-yy"
	});

	$( document ).on( 'focus', '.datepicker', function(){
		$( '.datepicker' ).datepicker({
			dateFormat : "dd-mm-yy"
		});
	});

	/*$(".datepicker").focus(function(){
        $(".datepicker").datepicker();
    });*/

    $( document ).on( 'click', '.mt-repeater-add', function(){
        $( '.datepicker' ).datepicker({
			dateFormat : "dd-mm-yy"
		});
    });

	

})(jQuery);