/*
* Advanced Widget Pack Adminstrative JavaScript functions
*/

(function($) {
	$(document).ready( function() {
		
		$('.awp-switch-link a').live('click',function(){
			var $tag = $(this).parent();
			$('a',$tag).toggleClass('active');
			var rel = $('a.active',$tag).attr('rel');
			$tag.next('.plugin-switch-value').val(rel);
		   
			if ((rel) == 'false'){
				$($tag.data('for')).hide();
				
			}else{
				$($tag.data('for')).show();
			}

			return false;
		});
	});
})(jQuery);