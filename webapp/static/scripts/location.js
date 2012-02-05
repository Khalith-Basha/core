    $(document).ready(function(){
        $("#countryId").live("change",function(){
            var pk_c_code = $(this).val();
            <?php
		if ($path == "admin") 
		{ ?>
                var url = '<?php echo osc_admin_base_url(true) . "?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php
		}
		else
		{ ?>
                var url = '<?php echo osc_base_url(true) . "?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
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
                            result += '<option value="">Select a region...</option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#region").before('<select name="regionId" id="regionId" ></select>');
                            $("#region").remove();

                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();

                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
                            $("#regionId").before('<input type="text" name="region" id="region" />');
                            $("#regionId").remove();

                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                        }
                        $("#regionId").html(result);
                        $("#cityId").html('<option selected value="">Select a city...</option>');
                    }
                 });
             } else {
                 // add empty select
                 $("#region").before('<select name="regionId" id="regionId" ><option value="">Select a region...</option></select>');
                 $("#region").remove();

                 $("#city").before('<select name="cityId" id="cityId" ><option value="">Select a city...</option></select>');
                 $("#city").remove();

                 if( $("#regionId").length > 0 ){
                     $("#regionId").html('<option value="">Select a region...</option>');
                 } else {
                     $("#region").before('<select name="regionId" id="regionId" ><option value="">Select a region...</option></select>');
                     $("#region").remove();
                 }
                 if( $("#cityId").length > 0 ){
                     $("#cityId").html('<option value="">Select a city...</option>');
                 } else {
                     $("#city").before('<select name="cityId" id="cityId" ><option value="">Select a city...</option></select>');
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
                            result += '<option selected value="">Select a city...</option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
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

        if( $("#countryId").attr('type').match(/select-one/) ) {
            if( $("#countryId").attr('value') == "")  {
                $("#regionId").attr('disabled',true);
            }
        }

    });


