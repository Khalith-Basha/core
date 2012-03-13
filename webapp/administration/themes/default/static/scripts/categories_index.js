
$( document ).ready( function()
	{
		$.jstree._themes = '/administration/themes/default/static/scripts/themes/';
		$( '#categoriesTree' ).jstree({
			plugins: [ 'themes', 'html_data', 'ui' ],
			core: {
				'html_titles': true
			}
		}).bind( 'select_node.jstree', function( event, data )
			{ 
				alert( data.rslt.obj.attr( 'id' ) );
			}
		);
	}
);
