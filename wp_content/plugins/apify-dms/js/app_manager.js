jQuery(document).ready( function($) {
	$("#app_key_secret_btn").click( function() {
        $.ajax({
            type: "POST",
            data: "app_id=" + $("#app_client_name").val() + "&action=generate_app_key_secret_action",
            url: ajaxurl,
            success: function( data ) {
				data = eval('('+data+')');
                $("#app_client_key").val( data.app_key );
				$("#app_client_secret").val( data.app_secret );
            },
			error: function() {
		    	alert('Ajax Response Error!');
			}
        });
    });
});
