
$(document).ready( function()
	{
		$("a[rel=image_group]").fancybox(
			{
				transitionIn: 'none',
				transitionOut: 'none',
				titlePosition: 'over',
				titleFormat: function(title, currentArray, currentIndex)
				{
					return '<span id="fancybox-title-over"><?php_e('Image', 'modern'); ?>  ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
				}
			}
		);
	}
);

