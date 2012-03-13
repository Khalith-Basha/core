
function editCategory( node )
{
	console.log( node[0].getAttribute( 'data-categoryId' ) );
}

function enableDisableCategory( node )
{
	console.log( node[0].getAttribute( 'data-categoryId' ) );
}

function deleteCategory( node )
{
	console.log( node[0].getAttribute( 'data-categoryId' ) );
}

$( document ).ready( function()
	{
		var treeContextMenu = [
			{
				label: 'Edit',
				action: editCategory,
				_disabled: false,
				_class: "class",
				separator_before: false,
				separator_after: true,
				icon: false,
				/* submenu: {} */
			},
			{
				label: 'Enable/Disable',
				action: enableDisableCategory,
				_disabled: false,
				_class: "class",
				separator_before: false,
				separator_after: true,
				icon: false,
				/* submenu: {} */
			},
			{
				label: 'Delete',
				action: deleteCategory,
				_disabled: false,
				_class: "class",
				separator_before: false,
				separator_after: true,
				icon: false,
			}
		];
		$.jstree._themes = '/administration/themes/default/static/scripts/themes/';
		$( '#categoriesTree' ).jstree({
			plugins: [ 'themes', 'html_data', 'ui', 'contextmenu' ],
			contextmenu: {
				items: treeContextMenu 
			},
			core: {
				'html_titles': true
			}
		}).bind( 'select_node.jstree', function( event, data )
			{ 
				console.log( data.rslt.obj.attr( 'id' ) );
			}
		);
	}
);

