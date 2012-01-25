    $("#catId").change(function(){
        var cat_id = $(this).val();
        var url = '<?php
		echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_<?php
		echo $case; ?>&catId=' + cat_id,
                dataType: 'html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });
    $(document).ready(function(){
        var cat_id = $("#catId").val();
        var url = '<?php
		echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_<?php
		echo $case; ?>&catId=' + cat_id,
                dataType: 'html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });

