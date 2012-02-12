
$( document ).ready(
	function()
	{
	    $(".sub_button").click(function(){
		$.post('<?php echo osc_base_url(true); ?>', {email:$("#alert_email").val(), userid:$("#alert_userId").val(), alert:$("#alert").val(), page:"ajax", action:"alerts"}, 
		    function(data){
			if( data == 1 ) { alert( __( 'You have sucessfully subscribed to the alert' ) ); }
			else if(data==-1) { alert( __( 'Invalid email address' ) ); }
			else { alert( __( 'There was a problem with the alert' ) );
			};
		});
		return false;
	    });
	}
);

