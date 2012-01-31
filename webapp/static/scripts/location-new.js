    $(document).ready(function(){

        $('#region').attr( "autocomplete", "off" );
        $('#city').attr( "autocomplete", "off" );

        $('#countryId').change(function(){
            $('#regionId').val('');
            $('#region').val('');
            $('#cityId').val('');
            $('#city').val('');            
        });


        $('#region').live('keyup.autocomplete', function(){
            $('#regionId').val('');
            $( this ).autocomplete({
                source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_regions&country="+$('#countryId').val(),
                minLength: 2,
                select: function( event, ui ) {
                    $('#cityId').val('');
                    $('#city').val('');
                    $('#regionId').val(ui.item.id);
                }
            });
        });

        $('#city').live('keyup.autocomplete', function(){
            $('#cityId').val('');
            $( this ).autocomplete({
                source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_cities&region="+$('#regionId').val(),
                minLength: 2,
                select: function( event, ui ) {
                    $('#cityId').val(ui.item.id);
                }
            });
        });



        /**
         * Validate form
         */

        // Validate description without HTML.
        $.validator.addMethod(
            "minstriptags",
            function(value, element) {
                altered_input = strip_tags(value);
                if (altered_input.length < 3) {
                    return false;
                } else {
                    return true;
                }
            },
            "<?php _e("Description: needs to be longer"); ?>."
        );

        // Code for form validation
        $("form[name=item]").validate({
            rules: {
                catId: {
                    required: true,
                    digits: true
                },
                <?php
		if (osc_price_enabled_at_items()) 
		{ ?>
                price: {                   
                    maxlength: 50
                },
                currency: "required",
                <?php
		} ?>
                <?php
		if (osc_images_enabled_at_items()) 
		{ ?>
                "photos[]": {
                    accept: "<?php
			echo osc_allowed_extension(); ?>"
                },
                <?php
		} ?>
                <?php
		if ($path == 'front') 
		{ ?>
                contactName: {
                    minlength: 3,
                    maxlength: 35
                },
                contactEmail: {
                    required: true,
                    email: true
                },
                <?php
		} ?>
                address: {
                    minlength: 3,
                    maxlength: 100
                }
            },
            messages: {
                catId: "<?php _e('Choose one category'); ?>.",
                <?php
		if (osc_price_enabled_at_items()) 
		{ ?>
                price: {
                    maxlength: "<?php _e("Price: no more than 50 characters"); ?>."
                },
                currency: "<?php _e("Currency: make your selection"); ?>.",
                <?php
		} ?>
                <?php
		if (osc_images_enabled_at_items()) 
		{ ?>
                "photos[]": {
                    accept: "<?php
			printf(__("Photo: must be %s"), osc_allowed_extension()); ?>."
                },
                <?php
		} ?>
                <?php
		if ($path == 'front') 
		{ ?>
                contactName: {
                    minlength: "<?php _e("Name: enter at least 3 characters"); ?>.",
                    maxlength: "<?php _e("Name: no more than 35 characters"); ?>."
                },
                contactEmail: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                <?php
		} ?>
                address: {
                    minlength: "<?php _e("Address: enter at least 3 characters"); ?>.",
                    maxlength: "<?php _e("Address: no more than 100 characters"); ?>."
                }
            },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

    /**
     * Strip HTML tags to count number of visible characters.
     */
    function strip_tags(html) {
        if (arguments.length < 3) {
            html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
        } else {
            var allowed = arguments[1];
            var specified = eval("["+arguments[2]+"]");
            if (allowed){
                var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
                html=html.replace(new RegExp(regex, 'gi'), '');
            } else{
                var regex='</?(' + specified.join('|') + ')\b[^>]*>';
                html=html.replace(new RegExp(regex, 'gi'), '');
            }
        }
        return html;
    }
    
    function delete_image(id, item_id,name, secret) {
        //alert(id + " - "+ item_id + " - "+name+" - "+secret);
        var result = confirm('<?php _e('This action can\\\'t be undone. Are you sure you want to continue?'); ?>');
        if(result) {
            $.ajax({
                type: "POST",
                url: '<?php
		echo osc_base_url(true); ?>?page=ajax&action=delete_image&id='+id+'&item='+item_id+'&code='+name+'&secret='+secret,
                dataType: 'json',
                success: function(data){
                    var class_type = "error";
                    if(data.success) {
                        $("div[name="+name+"]").remove();
                        class_type = "ok";
                    }
                    var flash = $("#flash_js");
                    var message = $('<div>').addClass('pubMessages').addClass(class_type).attr('id', 'FlashMessage').html(data.msg);
                    flash.html(message);
                    $("#FlashMessage").slideDown('slow').delay(3000).slideUp('slow');
                }
            });
        }
    }
    

