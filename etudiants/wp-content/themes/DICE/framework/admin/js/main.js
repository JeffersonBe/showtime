jQuery(document).ready(function()
{	
	// INITIALIZE THEME OPTIONS TABS
	jQuery('.btp-theme-options').each(function(){
		
		var navigation = jQuery('<ul class="btp-section-nav"></ul>');
		var viewport = jQuery('<div class="btp-section-viewport"></div>');
		
		var tabs = jQuery(this);
		
		jQuery('.btp-section-title', this).each(function(){
			var li = jQuery('<li class="btp-section-nav-item">' + jQuery(this).text()  + '</li>');
			var div = jQuery('<div class="btp-section-viewport-item"></div>').html(jQuery(this).next('.btp-section-content').html());
				
			navigation.append(li);			
			viewport.append(div);
			
			li.click(function() {	
				tabs.find('.btp-section-nav-item').removeClass('btp-section-nav-item-current');
				jQuery(this).addClass('btp-section-nav-item-current')
				tabs.find('.btp-section-viewport-item').hide();
				div.show();				
			});
			
			jQuery(this).remove();
		
		});	

		tabs.find('.btp-section').remove();	
		
		tabs.prepend(viewport);
		tabs.prepend(navigation);
		
		tabs.find('.btp-section-nav-item:first').addClass('btp-section-nav-item-current');
		tabs.find('.btp-section-viewport-item').hide();
		tabs.find('.btp-section-viewport-item:first').show();
	});
	
	
	jQuery('.btp-section-viewport-item').each(function(){
		
		var navigation = jQuery('<ul class="btp-subsection-nav"></ul>');
		var viewport = jQuery('<div class="btp-subsection-viewport"></div>');
		
		var tabs = jQuery(this);
		
		jQuery('.btp-subsection-title', this).each(function(){
			var li = jQuery('<li class="btp-subsection-nav-item">' + jQuery(this).text()  + '</li>');
			var div = jQuery('<div class="btp-subsection-viewport-item"></div>').html(jQuery(this).next('.btp-subsection-content').html());
				
			navigation.append(li);			
			viewport.append(div);
			
			li.click(function() {	
				tabs.find('.btp-subsection-nav-item').removeClass('btp-subsection-nav-item-current');
				jQuery(this).addClass('btp-subsection-nav-item-current')
				tabs.find('.btp-subsection-viewport-item').hide();
				div.show();				
			});
			
			jQuery(this).remove();
		
		});	

		tabs.find('.btp-subsection').remove();	
		
		tabs.prepend(viewport);
		tabs.prepend(navigation);
		
		tabs.find('.btp-subsection-nav-item:first').addClass('btp-subsection-nav-item-current');
		tabs.find('.btp-subsection-viewport-item').hide();
		tabs.find('.btp-subsection-viewport-item:first').show();
	});
	
	
	
	// INITIALIZE COLOR PICKER	
	jQuery('.btp-color-picker-toggle').click(function(){		
		var container = jQuery(this).siblings('.btp-color-picker-container').eq(0);
		var input = jQuery(this).siblings('input').eq(0);
		var preview = jQuery(this).siblings('.btp-color-picker-preview').eq(0);
		
		preview.addClass('on');
		container.addClass('on');
		
		container.farbtastic(function callback(color){			 			 
			 jQuery('.btp-color-picker-preview-new', preview).css('background-color', color);
			 input.attr('value', color);
			 
		 });		
		jQuery.farbtastic(container).setColor(input.attr('value'));
		
		//jQuery('.btp-color-picker-preview-current', preview).click(function(){
		//	alert('clicl');	
		//	jQuery('.btp-color-picker-preview-new').css('background-color', jQuery(this).css('background-color'));	
		//});
		
		jQuery(document).not(preview, container).mousedown(function(eventObject){			
			
			jQuery('.btp-color-picker-preview-current', preview).css('background-color', jQuery('.btp-color-picker-preview-new', preview).css('background-color'));
			jQuery('.btp-option-unit-color .btp-color-picker-preview, .btp-option-unit-color .btp-color-picker-container').removeClass('on');
			
		});
		
	});
	
	jQuery('.btp-option-unit-color input').blur(function(){		
		var preview = jQuery(this).siblings('.btp-color-picker-preview').eq(0);
				
		jQuery('.btp-color-picker-preview-current', preview).css('background-color', jQuery(this).attr('value'));
	});
	
	
	// INITIALIZE FORM UNITS
	jQuery('.btp-form-unit .btp-help').each(function(){		
		var context = this;		
		jQuery('.btp-help-content',context).hide();
		jQuery('.btp-help-toggle', context).toggleClass('btp-help-toggle-off').click(function(){
			jQuery(this).toggleClass('btp-help-toggle-on').toggleClass('btp-help-toggle-off');
			jQuery('.btp-help-content', context).toggle('fast');			
		});
	});
	
	// INITIALIZE SHORTCODE GENERATOR
	if ( typeof tinymce !== "undefined" && tinymce) {
		var scg;
	
		tinymce.create('tinymce.plugins.btp_shortcode_generator', {    	
			init : function(ed, url){
		    	scg = new btpShortcodeGenerator(ed, 'btp-shortcode-generator');    		
		    	
		        ed.addButton('btp_shortcode_generator', {
		        	title : 'Shortcode Generator',
		            	onclick : function() {            	
		            		scg.showUI();
		                },
		            image: url + "/../images/icon_shortcode.png"
		        });
		    },
		    createControl : function(n, cm) {
		    	return null;
		    }
		});
		  
		tinymce.PluginManager.add('btp_shortcode_generator', tinymce.plugins.btp_shortcode_generator);
	}
	
});

//Function to convert hex format to a rgb color
function rgbToHex(rgb){
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	return "#" +
		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
}