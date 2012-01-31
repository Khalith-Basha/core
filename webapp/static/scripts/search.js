
$(document).ready( function()
	{
		var oPattern = $('input[name=sPattern]');
		if(oPattern.val() == sQuery) {
		    oPattern.css('color', 'gray');
		}
		oPattern.click(function(){
		    if($('input[name=sPattern]').val() == sQuery) {
			$('input[name=sPattern]').val('');
			$('input[name=sPattern]').css('color', '');
		    }
		});
		oPattern.blur(function(){
		    if($('input[name=sPattern]').val() == '') {
			$('input[name=sPattern]').val(sQuery);
			$('input[name=sPattern]').css('color', 'gray');
		    }
		});
		oPattern.keypress(function(){
		    $('input[name=sPattern]').css('background','');
		});
	}
);

function doSearch()
{
        if($('input[name=sPattern]').val() == sQuery)
	{
            return false;
        }
        if($('input[name=sPattern]').val().length < 3)
	{
            $('input[name=sPattern]').css('background', '#FFC6C6');
            return false;
        }
        return true;
}

                    $(function() {
                        function log( message ) {
                            $( "<div/>" ).text( message ).prependTo( "#log" );
                            $( "#log" ).attr( "scrollTop", 0 );
                        }

                        $( "#sCity" ).autocomplete({
                            source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location",
                            minLength: 2,
                            select: function( event, ui ) {
                                log( ui.item ?
                                    "Selected: " + ui.item.value + " aka " + ui.item.id :
                                    "Nothing selected, input was " + this.value );
                            }
                        });
                    });
                    
                    function checkEmptyCategories() {
                        var n = $("input[id*=cat]:checked").length;
                        if(n>0) {
                            return true;
                        } else {
                            return false;
                        }
                    }

