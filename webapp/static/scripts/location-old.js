    $(document).ready(function(){
        $("#countryId").live("change",function(){
            var pk_c_code = $(this).val();
            <?php
		if ($path == "admin") 
		{ ?>
                var url = '<?php
			echo osc_admin_base_url(true) . "?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php
		}
		else
		{ ?>
                var url = '<?php
			echo osc_base_url(true) . "?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php
		}; ?>
            var result = '';

            if(pk_c_code != '') {

                $("#regionId").attr('disabled',false);
                $("#cityId").attr('disabled',true);

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        
                        if(length > 0) {

                            result += '<option value=""><?php
		_e("Select a region..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }

                            $("#region").before('<select name="regionId" id="regionId" ></select>');
                            $("#region").remove();

                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                            
                            $("#regionId").val("");

                        } else {

                            $("#regionId").before('<input type="text" name="region" id="region" />');
                            $("#regionId").remove();
                            
                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                            
                        }

                        $("#regionId").html(result);
                        $("#cityId").html('<option selected value=""><?php
		_e("Select a city..."); ?></option>');
                    }
                 });

             } else {

                 // add empty select
                 $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php
		_e("Select a region..."); ?></option></select>');
                 $("#region").remove();
                 
                 $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php
		_e("Select a city..."); ?></option></select>');
                 $("#city").remove();

                 if( $("#regionId").length > 0 ){
                     $("#regionId").html('<option value=""><?php
		_e("Select a region..."); ?></option>');
                 } else {
                     $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php
		_e("Select a region..."); ?></option></select>');
                     $("#region").remove();
                 }
                 if( $("#cityId").length > 0 ){
                     $("#cityId").html('<option value=""><?php
		_e("Select a city..."); ?></option>');
                 } else {
                     $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php
		_e("Select a city..."); ?></option></select>');
                     $("#city").remove();
                 }
                 $("#regionId").attr('disabled',true);
                 $("#cityId").attr('disabled',true);
             }
        });

        $("#regionId").live("change",function(){
            var pk_c_code = $(this).val();
            <?php
		if ($path == "admin") 
		{ ?>
                var url = '<?php
			echo osc_admin_base_url(true) . "?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php
		}
		else
		{ ?>
                var url = '<?php
			echo osc_base_url(true) . "?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php
		}; ?>

            var result = '';

            if(pk_c_code != '') {
                
                $("#cityId").attr('disabled',false);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option selected value=""><?php
		_e("Select a city..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }

                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                        } else {
                            result += '<option value=""><?php
		_e('No results') ?></option>';
                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                        }
                        $("#cityId").html(result);
                    }
                 });
             } else {
                $("#cityId").attr('disabled',true);
             }
        });

        if( $("#regionId").attr('value') == "")  {
            $("#cityId").attr('disabled',true);
        }

        if($("#countryId").length != 0) {
            if( $("#countryId").attr('type').match(/select-one/) ) {
                if( $("#countryId").attr('value') == "")  {
                    $("#regionId").attr('disabled',true);
                }
            }
        }

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
            "<?php
		_e("Description: needs to be longer"); ?>."
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
                    maxlength: 15
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
                regionId: {
                    required: true,
                    digits: true
                },
                cityId: {
                    required: true,
                    digits: true
                },
                cityArea: {
                    minlength: 3,
                    maxlength: 50
                },
                address: {
                    minlength: 3,
                    maxlength: 100
                }
            },
            messages: {
                catId: "<?php
		_e('Choose one category'); ?>.",
                <?php
		if (osc_price_enabled_at_items()) 
		{ ?>
                price: {
                    maxlength: "<?php
			_e("Price: no more than 50 characters"); ?>."
                },
                currency: "<?php
			_e("Currency: make your selection"); ?>.",
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
                    minlength: "<?php
			_e("Name: enter at least 3 characters"); ?>.",
                    maxlength: "<?php
			_e("Name: no more than 35 characters"); ?>."
                },
                contactEmail: {
                    required: "<?php
			_e("Email: this field is required"); ?>.",
                    email: "<?php
			_e("Invalid email address"); ?>."
                },
                <?php
		} ?>
                regionId: "<?php
		_e("Select a region"); ?>.",
                cityId: "<?php
		_e("Select a city"); ?>.",
                cityArea: {
                    minlength: "<?php
		_e("City area: enter at least 3 characters"); ?>.",
                    maxlength: "<?php
		_e("City area: no more than 50 characters"); ?>."
                },
                address: {
                    minlength: "<?php
		_e("Address: enter at least 3 characters"); ?>.",
                    maxlength: "<?php
		_e("Address: no more than 100 characters"); ?>."
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
        var result = confirm('<?php
		_e('This action can\\\'t be undone. Are you sure you want to continue?'); ?>');
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
 
