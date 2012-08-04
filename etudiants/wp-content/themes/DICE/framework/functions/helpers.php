<?php

	/** 
	 * Composes input[type="hidden"] tag 
	 */
	function btp_input_hidden_tag( $id, $value = null, $args = array() ) {
		$class = 'btp-input-hidden ';
		if ( isset( $args['class'] ) )
			$class .= $args['class'];
				
		/* Compose output */
		$out = '';
		$out .= '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '"';
		$out .= ' value="' . esc_attr( $value ) . '"';
		$out .= '/>';
		
		return $out;		
	}
	
	/**
	 * Composes input[type="text"] tag 
	 */
	function btp_input_text_tag( $id, $value = null, $args = array() ) {
		$class = 'btp-input-text ';
		if ( isset( $args['class'] ) )
			$class .= $args['class'];
				
		/* Compose output */
		$out = '';
		$out .= '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '"';
		$out .= ' value="' . esc_attr( $value ) . '"';
		$out .= '/>';
		
		return $out;		
	}
	
	

	/** 
	 * Composes input[type="checkbox"] tag
	 */
	function btp_input_checkbox_tag( $id, $value = null, $args = array() ) {
		$class = 'btp-input-checkbox ';
		if ( isset( $args['class'] ) )
			$class .= $args['class'];
				
		/* Compose output */
		$out = '';
		$out .= '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" value="true"';
		$out .= $value ? ' checked="checked"' : '';
		$out .= '/>';
		
		return $out;		
	}
	
	/**
	 * Composes select tag
	 */	
	function btp_select_tag( $id, $value = null, $args = array() ) {			    	
        $options_indexed = array_values( $args['options'] ) === $args['options'];
        
		$class = '';
		if ( isset( $args['class'] ) )
			$class .=$args['class']; 
					
		/* Compose output */
		$out = '';
		$out .= '<select id="' . $id . '" name="' . $id . '" class="' . esc_attr( $class ) . '">';
		
		if ( isset( $args['include_empty'] ) )
			$out .= '<option value="">' . htmlspecialchars( strip_tags( $args['include_empty'] ) ) . '</option>';	
			
		foreach ( $args['options'] as $k => $v ) {
			if ( $options_indexed ) 
				$k = $v;
								
			$selected = ( $k == $value ) ? ' selected="selected"' : '';
				
			$out .= '<option value="' . esc_attr( $k ) . '"' . $selected . '>' . htmlspecialchars( strip_tags( $v ) ) . '</option>';
		}
		            
		$out .= '</select>';

		return $out;
	}
	
	
	
	/**
	 * Build bridge between theme options and jQuery
	 */
	function btp_the_jquery_metadata() {
    	global $btp_theme_options, $btp_theme_slug;
    	
    	$out = '';
    	$out .= '<div id="metadata" class="metadata { ';  

    	$out .= 'template_url: \'' . htmlspecialchars( strip_tags( get_template_directory_uri() ) ) . '\','; 
		
        foreach ( $btp_theme_options as $option_id => $option_def ) {        
        	if ( isset( $option_def['view']['metadata'] ) && $option_def['view']['metadata'] == true ) {
        		$key = str_replace( $btp_theme_slug.'_', '', $option_id );
        		$value = get_option( $option_id );
        		
        		$out .= htmlspecialchars( strip_tags( $key ) ) . ': \'' . htmlspecialchars( strip_tags( $value ) ) . '\','; 
        	}
        }
        	
        $out = trim( $out, ',' );	    	
    	$out .= ' }"></div>';
    	
    	return $out;	
    }
	
	
	
	
	
	
	/**
	 * Renders option unit of type section_start
	 */
	function btp_view_render_func_section_start( $id, $def ) {
        ?>
        <div class="btp-section">              
        	<div class="btp-section-title">
            	<h2><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></h2>
            </div>
            <div class="btp-section-content">				        
        <?php
    }
    
    /**
     * Renders option unit of type section_end
     */
	function btp_view_render_func_section_end( $id, $def ) {
        ?>         		
			</div><!-- END: .btp-section-content -->
		</div><!-- END: .btp-section -->
        <?php
    }
    
    /**
     * Renders option unit of type subsection_start 
     */
	function btp_view_render_func_subsection_start( $id, $def ) {	
        ?>       
        <div class="btp-subsection">              
        	<div class="btp-subsection-title">
            	<h3><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></h3>
            </div>
            <div class="btp-subsection-content">				        
        <?php
    }
    
    /**
     * Render option unit of type subsection_end 
     */
	function btp_view_render_func_subsection_end( $id, $def ) {
        ?>		
			</div><!-- END: .btp-subsection-content -->
		</div><!-- END: .btp-subsection -->
        <?php
    }
    
    
    
    /**
     * Renders option unit of type header 
     */
	function btp_view_render_func_header( $id, $def ) {	
        ?>   
        <div class="btp-header">
        	<h4><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></h4>            
        </div>				        
        <?php
    }
    
    
	
	
	
	/**
	 * Renders option unit of type string 
	 */
	function btp_view_render_func_input_text( $id, $def, $value = null ) {        
    	?>     
    	<div class="btp-option-unit btp-option-unit-string">		  
            <div class="btp-label">
            	<label for="<?php echo esc_attr( $id ); ?>"><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></label>
            </div>
            <div class="btp-field">
            	<?php echo btp_input_text_tag( $id, $value, $def['view'] ); ?>            	
            </div>           
            <?php if ( isset( $def['view']['hint'] ) ): ?>
            <div class="btp-hint">
            	<?php echo $def['view']['hint']; ?>
            </div>
            <?php endif;?>
        </div>       
        <?php
    }
    
    /**
     * Renders option unit of type textarea
     * 
     * @param $id
     * @param $def
     * @param $value
     */
    function btp_view_render_func_textarea( $id, $def, $value = null ) {        
    	?>     
    	<div class="btp-option-unit btp-option-unit-textarea">		  
            <div class="btp-label">
            	<label for="<?php echo esc_attr( $id ); ?>"><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></label>
            </div>
            <div class="btp-field">            
            	<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>"><?php echo $value; ?></textarea>            	            	
            </div>
            <?php if ( isset( $def['view']['hint'] ) ): ?>
            <div class="btp-hint">
            	<?php echo $def['view']['hint']; ?>
            </div>
            <?php endif;?>           
        </div>       
        <?php
    }
    
    
    
    /**
     * Renders option unit of type checkbox 
     * 
     * @param $id
     * @param $def
     * @param $val
     */
    function btp_view_render_func_input_checkbox( $id, $def, $val = null ) {
    	?>
    	<div class="btp-option-unit btp-option-unit-boolean">
    		<div class="btp-label">
    			<label for="<?php echo esc_attr( $id ); ?>"><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></label>
    		</div>
    		<div class="btp-field">
    			<?php echo btp_input_checkbox_tag( $id, $val ); ?>		
    		</div>    		   	
    	</div>
    	<?php   
    }
	
	
	
	/**
	 * Renders option unit of type select
	 * 
	 * @param $id
	 * @param $def
	 * @param $val
	 */
    function btp_view_render_func_select( $id, $def, $val ) {    	
    	if ( is_array( $def['model']['options'] ) )
    		$options = $def['model']['options'];
    	else
    		$options = call_user_func( $def['model']['options'] );
    				
		$args = array();
		$args['options'] = $options;

		if ( isset( $def['model']['null'] ) )
			$args['include_empty'] = $def['model']['null'];
		
    	?> 
    	  
    	<div class="btp-option-unit btp-option-unit-select">
            <div class="btp-label">
            	<label for="<?php echo esc_attr( $id ); ?>"><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></label>
            </div>
            <div class="btp-field">
            	<?php echo btp_select_tag( $id, $val, $args ); ?>    
            </div>
            <?php if ( isset( $def['view']['hint'] ) ): ?>
            <div class="btp-hint">
            	<p><?php echo htmlspecialchars( strip_tags( $def['view']['hint'] ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Renders option unit of type color
     * 
     * @param $id
     * @param $def
     * @param $val
     */
	function btp_view_render_func_color( $id, $def, $val ) {        
    	?>     
    	<div class="btp-option-unit btp-option-unit-color">		  
            <div class="btp-label">
            	<label for="<?php echo esc_attr( $id ); ?>"><?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?></label>
            </div>
            <div class="btp-field">            	
            	<span class="btp-color-picker-preview">
            		<span class="btp-color-picker-preview-current" style="background-color: <?php echo $val; ?>;" ></span>
            		<span class="btp-color-picker-preview-new"></span>
            	</span>
            	<?php echo btp_input_text_tag( $id, $val ); ?>            	
            	<span class="btp-color-picker-toggle">Color Picker</span>            	
            	<div class="btp-color-picker-container"></div>
            </div>
            <?php if ( isset($def['view']['hint'] ) ): ?>
            <div class="btp-hint">
            	<p><?php echo htmlspecialchars( strip_tags( $def['view']['hint'] ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>       
        <?php
    }
    

    
    /**
     * Renders option unit of type feed
     * 
     * @param $id
     * @param $def
     * @param $value
     */    
    function btp_view_render_func_feed( $id, $def, $value ) {
		global $btp_theme_slug;			
    	?>    	
    	<div class="btp-option-unit btp-option-unit-feed"> 	
        	<h3>        		      		
        		<img src="<?php echo get_template_directory_uri() . '/images/icons/' . str_replace( $btp_theme_slug . '_feeds_', '', $id ) . '.png'; ?>" alt="" />
        		<?php echo htmlspecialchars( strip_tags( $def['view']['label'] ) ); ?>        		
        	</h3>
        	
        		<div class="btp-option-unit">
        			<div class="btp-label">
        				<label for="<?php echo esc_attr( $id . '_is_active' ); ?>"><?php echo htmlspecialchars( strip_tags( __( 'Is active?', 'btp_theme') ) ); ?></label>
        			</div>
        			
        			<div class="btp-field">
        				<?php echo btp_input_checkbox_tag( $id . '_is_active', $value['is_active'] ); ?>
        			</div>
        		</div>
        		
        		<div class="btp-option-unit">
        			<div class="btp-label">
        				<label for="<?php echo esc_attr( $id . '_url' ); ?>"><?php echo htmlspecialchars( strip_tags( __( 'URL', 'btp_theme' ) ) ); ?></label>
        			</div>
        			<div class="btp-field">        				
        				<?php echo btp_input_text_tag( $id . '_url', $value['url'] ); ?>
        			</div>
        		</div>
        		
        		<div class="btp-option-unit">
        			<div class="btp-label">
        				<label for="<?php echo esc_attr( $id . '_label' ); ?>"><?php echo htmlspecialchars( strip_tags( __( 'Label', 'btp_theme' ) ) ); ?></label>
        			</div>
        			<div class="btp-field">
        				<?php echo btp_input_text_tag( $id . '_label', $value['label'] );?>
        			</div>
        		</div>        		
        		
        		<div class="btp-option-unit">
        			<div class="btp-label">
        				<label for="<?php echo esc_attr( $id . '_caption' ); ?>"><?php echo htmlspecialchars( strip_tags( __( 'Caption', 'btp_theme' ) ) ); ?></label>
        			</div>
        			<div class="btp-field">
        				<?php echo btp_input_text_tag( $id . '_caption', $value['caption'] );?>
        			</div>        			
        		</div>
        </div>
        <?php
    	
    } 
   
	
	
	
	/**
	 * Renders metadata array as a HTML tag with proper markup for jQuery Metadata Plugin
	 * 
	 * @param array $metadata
	 * @param string $html_tag
	 * @param string $css_id
	 * @param string $css_class
	 * @param bool $echo
	 */
	function btp_render_metadata( $metadata, $html_tag = 'div', $css_id = null, $css_class = null, $echo = true ) {
		$out = '';
		$out .= '<' . $html_tag . ' class="metadata { ';
		
		foreach( $metadata as $key => $value ) {
			if ( $value === true )
				$out .= $key . ': \'true\',';
			elseif ( $value === false )	
				$out .= $key . ': \'false\',';
			else
				$out .= $key . ': \'' . $value . '\',';	
		}
			
		$out = trim( $out, ',' );		
		$out .= ' } "></' . $html_tag . '>';
		
		if ( $echo )
			echo $out;
		else	
			return $out;		
	}	
	
	
	/**
	 * Renders favicon
	 * 
	 * @param string $src
	 * @param bool $echo
	 */
	function btp_render_favicon( $src = null, $echo = true ) {
		if( null === $src )
			$src = btp_get_theme_option( 'general_favicon_src');

		$out = '';
		$out .= strlen( $src ) ? '<link rel="shortcut icon" href="' . esc_attr( $src ) . '" />' : '';

		if ( $echo )
			echo $out;
		else	
			return $out;
	}
	
	
	/**
	 * Renders apple touch icon
	 * 
	 * @param unknown_type $src
	 * @param unknown_type $echo
	 * @return string
	 */
	function btp_render_apple_touch_icon( $src = null, $echo = true ) {
		if( null === $src )
			$src = btp_get_theme_option( 'general_apple_touch_icon_src' );

		$out = '';
		$out .= strlen( $src ) ? '<link rel="apple-touch-icon" href="' . esc_attr( $src ) . '"/>' : '';

		if ( $echo )
			echo $out;
		else	
			return $out;
	}
	
?>