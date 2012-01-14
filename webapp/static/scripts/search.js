
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

