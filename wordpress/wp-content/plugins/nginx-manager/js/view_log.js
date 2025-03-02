
// Display log messages filtering by the log level

function showLog(logLevel) {
	
	jQuery("#log_filter li").css('font-weight', 'normal')
	jQuery("#log_filter li#"+logLevel+"_filter").css('font-weight', 'bold')
	
	jQuery('#nginxm_error_list li').each(function (index) {
		if (jQuery(this).hasClass(logLevel) || (logLevel == 'all')) {
			jQuery(this).css('display', 'block')
		} else {
			jQuery(this).css('display', 'none')
		}
	})
	
}
