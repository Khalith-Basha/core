    $(function() {
	
	var list_original = '';
	
	$('.sortable').nestedSortable({
	    disableNesting: 'no-nest',
	    forcePlaceholderSize: true,
	    handle: 'div',
	    helper:	'clone',
	    listType: 'ul',
	    items: 'li',
	    maxLevels: 2,
	    opacity: .6,
	    placeholder: 'placeholder',
	    revert: 250,
	    tabSize: 25,
	    tolerance: 'pointer',
	    toleranceElement: '> div',
	    create: function(event, ui){
		list_original = $('.sortable').nestedSortable('serialize');
	    },
	    stop: function(event, ui) { 
		var list = '';
		list = $('.sortable').nestedSortable('serialize');
		if(list_original != list) {
		    $.ajax({
			url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=categories_order&"; ?>"+list,
			context: document.body,
			success: function(res){
			    var ret = eval( "(" + res + ")");
			    var message = "";
			    if(ret.error) { 
				message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png'); ?>"/>';
				message += ret.error; 

			    }
			    if(ret.ok){ 
				message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png'); ?>"/>';
				message += ret.ok; 
			    }

			    $("#jsMessage").fadeIn("fast");
			    $("#jsMessage").html(message);
			    setTimeout(function(){
				$("#jsMessage").fadeOut("slow", function () {
				    $("#jsMessage").html("");
				});
			    }, 3000);
			},
			error: function(){
			    $("#jsMessage").fadeIn("fast");
			    $("#jsMessage").html("<?php _e('Ajax error, try again.'); ?>");
			    setTimeout(function(){
				$("#jsMessage").fadeOut("slow", function () {
				    $("#jsMessage").html("");
				});
			    }, 3000);
			}
		    });

		    list_original = list;
		}
	    }
	});
    });

    
    list_original = $('.sortable').nestedSortable('serialize');
    
    function show_iframe(class_name, id) {

	$('.edit #settings_form').remove();

	var name = 'frame_'+ id ; 
	var id_  = 'frame_'+ id ;
	var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=category_edit_iframe&id='+id;
	$.ajax({
	    url: url,
	    context: document.body,
	    success: function(res){
		$('div.'+class_name).html(res);
		$('div.'+class_name).fadeIn("fast");
	    }
	});
	
	return false;
    }
    
    function delete_category(id){
	var answer = confirm('<?php _e('WARNING: This will also delete the items under that category. This action cann not be undone. Are you sure you want to continue?'); ?>');
	if(answer){
	    var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_category&id='+id;
	    $.ajax({
		url: url,
		context: document.body,
		success: function(res){
		    var ret = eval( "(" + res + ")");
		    var message = "";
		    if(ret.error) { 
			message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png'); ?>"/>';
			message += ret.error; 
		    }
		    if(ret.ok){
			message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png'); ?>"/>';
			message += ret.ok;
			
			$('#list_'+id).fadeOut("slow");
			$('#list_'+id).remove();
		    }
		    
		    $("#jsMessage").fadeIn("fast");
		    $("#jsMessage").html(message);
		    setTimeout(function(){
			$("#jsMessage").fadeOut("slow", function () {
			    $("#jsMessage").html("");
			});
		    }, 3000);

		},
		error: function(){
		    $("#jsMessage").fadeIn("fast");
		    $("#jsMessage").html("<?php _e('Ajax error, try again.'); ?>");

		    setTimeout(function(){
			$("#jsMessage").fadeOut("slow", function () {
			    $("#jsMessage").html("");
			});
		    }, 3000);
		}
	    });
	}
	return false;
    }
    
    function enable_cat(id){
	
	var enabled = '';
	if( $('div[category_id='+ id +']').hasClass('disabled') ){
	    enabled = 1;
	} else {
	    enabled = 0;
	}
	var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=enable_category&id='+id+'&enabled='+enabled;
	
	$.ajax({
	    url: url,
	    context: document.body,
	    success: function(res){
		var ret = eval( "(" + res + ")");
		var message = "";
		if(ret.error) { 
		    message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png'); ?>"/>';
		    message += ret.error; 
		}
		if(ret.ok){ 
		    message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png'); ?>"/>';
		    message += ret.ok;
		    if(enabled == 0) {
			$('div[category_id='+ id +']').addClass('disabled');
			$('div[category_id='+ id +']').removeClass('enabled');
			
			$('div[category_id='+ id +']').find('a.enable').text('<?php _e('Enable'); ?>');
			
			for(var i = 0; i < ret.affectedIds.length; i++) {
			    id =  ret.affectedIds[i].id ;
			    $('div[category_id='+ id +']').addClass('disabled');
			    $('div[category_id='+ id +']').removeClass('enabled');
			    
			    $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Enable'); ?>');
			}
		    } else {
			$('div[category_id='+ id +']').removeClass('disabled');
			$('div[category_id='+ id +']').addClass('enabled');
			
			$('div[category_id='+ id +']').find('a.enable').text('<?php _e('Disable'); ?>');
			
			for(var i = 0; i < ret.affectedIds.length; i++) {
			    id =  ret.affectedIds[i].id ;
			    $('div[category_id='+ id +']').removeClass('disabled');
			    $('div[category_id='+ id +']').addClass('enabled');
			 
			    $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Disable'); ?>');
			}
		    }
		}

		$("#jsMessage").fadeIn("fast");
		$("#jsMessage").html(message);

		setTimeout(function(){
		    $("#jsMessage").fadeOut("slow", function () {
			$("#jsMessage").html("");
		    });
		}, 3000);

	    },
	    error: function(){
		$("#jsMessage").fadeIn("fast");
		$("#jsMessage").html("<?php _e('Ajax error, try again.'); ?>");
		setTimeout(function(){
		    $("#jsMessage").fadeOut("slow", function () {
			$("#jsMessage").html("");
		    });
		}, 3000);
	    }
	});
    };

