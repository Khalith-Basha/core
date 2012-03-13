
$( document ).ready( function()
	{
		$( 'button.HistoryBack' ).click( function()
			{
				history.go( -1 );
			}
		);
		tinyMCE.init(
			{
				mode : "textareas",
				theme : "advanced",
				skin: "o2k7",
				width: "70%",
				height: "140px",
				skin_variant : "silver",
				theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,link,unlink,separator,image,code",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_align : "left",
				theme_advanced_toolbar_location : "top",
				plugins : "media",
				entity_encoding : "raw",
				theme_advanced_buttons1_add : "media"
			}
		);
	}
);

