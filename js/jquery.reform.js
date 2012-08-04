/* reForm jquery class | www.r0ash.com*/
(function($){  
 $.fn.reform = function(options) {  
	var defaults	= {
		field_id_prefix: 'reform-',
		ajax_loader_id:	'reform_ajax_loader_id',
		message_placeholder_id: 'reform_message_placeholder',
		error_message_html:	'<div class="reform_field_error_container"><span class="reform_error_field">&#9650;&nbsp;%message%</span></div>',
		fatal_error_message: 'Internal Server Error',
		field_error_container_class: 'reform_field_error_container',
		message_placeholder_error_class: 'reform_error',
		message_placeholder_success_class: 'reform_success'
		};
	var options = $.extend(defaults, options);
	$('#'+options.ajax_loader_id).hide();
	var theForm	= $(this);
	theForm.submit(function(e){
		$('#'+options.ajax_loader_id).show();
		$('#'+theForm.attr("id") + ' input[type=submit]').attr("disabled", "disabled");
		$.uniform.update('#'+theForm.attr("id") + ' input[type=submit]');
		e.preventDefault();
		theForm.ajaxSubmit({
			dataType: 'json',
			type: 'post',
			iframe: true,
			async: false,
			data: { 'is_ajax': 1 },
			success: function (j) {
                
				$('input[type=submit]').removeAttr("disabled");
				$.uniform.update('#'+theForm.attr("id") + ' input[type=submit]');
				$('#'+options.ajax_loader_id).hide();
				$('#'+theForm.attr("id") + ' .'+options.field_error_container_class).fadeOut('slow').remove();
				if (j.result==false) {
					
					/* Failure */
					
					/* Clean JSON encoded message to render HTML correctly */
					j.message.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
					j.message.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');

					/* Top message */
					if (j.message!="") {
						var placeholder_parent	= $("#"+options.message_placeholder_id).parent();
						if ( (placeholder_parent.css('display') == 'none' ) || (placeholder_parent.css('display') == '' ) ) {
							placeholder_parent.css('display','block');
						}
						$("#"+options.message_placeholder_id).hide().addClass(options.message_placeholder_error_class).html(j.message).fadeIn(100);
					}
					/* Validation messages related to each form-field */
					$.each(j.fields, function(i,v){
						
						/* Clean JSON encoded message to render HTML correctly */
						var reason    = v.reason;
						if ( reason != null ) {
                            reason    = v.reason.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                            reason        = reason.replace(/&amp;lt;/g, '&lt;').replace(/&amp;gt;/g, '&gt;');
						}
						options.error_message_html	= options.error_message_html.replace(/&gt;/g, '>').replace(/&lt;/g,'<');
						/* Create field validation message block */
						var error_el	= $(options.error_message_html.replace('%message%', reason)).fadeIn("slow");
						
						/* Attach message to field's container */
						$("#"+options.field_id_prefix+v.field_name).append(error_el);
						
						/* Handle captcha specially */
						if ( typeof Recaptcha === 'object' ) {
							if ( v.field_name == 'recaptcha_challenge_field' ) {
								$("#recaptcha_widget_div").after(error_el);
								Recaptcha.reload();
							}
						}
					});
					
					/* animate to errorneous filed if its out of current viewport */
					var offset	= 50;
					var topPosition	= $(window).scrollTop() - offset;
					var targetParent= $('#'+theForm.attr("id") +' .'+options.field_error_container_class).parent();
					if ( targetParent != null ) {
                        var targetPostition = ( targetParent.offset() == null) ? 0 : targetParent.offset().top;
                        if (targetPostition < topPosition) {
                            $("html,body").animate( { scrollTop: targetPostition - offset }, 300 );
                        }
					}
				} else {
					
					/* Success */
					
					/* Clean JSON encoded message to render HTML correctly */
					j.message  = j.message.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
					j.message  = j.message.replace(/&amp;lt;/g, '&lt;').replace(/&amp;gt;/g, '&gt;');
					
					$("#"+ theForm.attr("id")+" .reform_field").parent().fadeOut();
					var placeholder_parent	= $("#"+options.message_placeholder_id).parent();
					if ( (placeholder_parent.css('display') == 'none' ) || (placeholder_parent.css('display') == '' ) ) {
						placeholder_parent.css('display','block');
					}
					$("#"+options.message_placeholder_id).addClass(options.message_placeholder_success_class).html(j.message).fadeIn("slow");
					
				}
			},
			error: function() {
				$('#'+options.ajax_loader_id).hide();
				$('#'+theForm.attr("id") + ' input[type=submit]').removeAttr("disabled");
				$.uniform.update('#'+theForm.attr("id") + ' input[type=submit]');
				$("#"+options.message_placeholder_id).addClass(options.message_placeholder_error_class).html(options.fatal_error_message);
			}
		});
		return false;
	});
    return this.each(function() {  
    });  
 };
})(jQuery); 