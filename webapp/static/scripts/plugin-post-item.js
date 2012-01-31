    $("#catId").change(function(){
    var operation = 'add';
        var cat_id = $(this).val();
        var url = '<?php echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_' + operation + '&catId=' + cat_id,
                dataType: 'html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });
    $(document).ready(function(){
    var operation = 'add';
        var cat_id = $("#catId").val();
        var url = '<?php echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_' + operation + '&catId=' + cat_id,
                dataType: 'html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });

