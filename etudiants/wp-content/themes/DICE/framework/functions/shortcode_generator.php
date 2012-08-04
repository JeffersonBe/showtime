<?php

class BTP_Shortcode_Generator_Item {
	
	protected	$_name,
				$_args;
	
	
	public function __construct($name, $args = array()) {
		$this->_name = $name;
		
		$this->_args = array_merge(
			array(
				'label'			=> $name,
				'help'			=> null,
				'attributes' 	=> array(),
				'content'		=> null,
				'display'		=> 'inline', 
				'result'		=> null,
			),
			$args
		);
	}

	public function hasAttributes() {
		return count( $this->_args['attributes'] ) ? true : false;	
	}	
	public function getAttributes() {
		return $this->_args['attributes'];	
	}
	
	public function hasContent() {
		return $this->_args['content'] ? true : false;
	}
	public function getContent() {
		return $this->_args['content'];	
	}

	public function hasResult() {
		return $this->_args['result'] ? true : false;
	}
	public function getResult() {
		return $this->_args['result'];	
	}
	
	public function setName( $v ) { $this->_name = $v; }	
	public function getName() { return $this->_name; }

	public function getLabel() { return $this->_args[ 'label' ]; }
	public function getHelp() { return $this->_args[ 'help' ]; }
	
	
	/**
	 * Renders UI
	 */
	public function render() {		
		$out = '';		
		$out .= '<div class="btp-shortcode">';
		
			$out .= '<div class="btp-shortcode-meta"><input type="hidden" name="display" value="' . esc_attr( $this->_args['display'] ) . '"/></div>';
		
			$out .= '<h2>' . esc_html( $this->getLabel() ) . '</h2>';
			
			if ( strlen( $this->getHelp() )  ) {
				$out .= '<div class="btp-shortcode-help">';
					$out .= $this->getHelp();
				$out .= '</div>';
			}
			
			if ( !$this->hasResult() ) {
				
				if( $this->hasAttributes() || $this->hasContent() )
					$out .= '<div class="btp-message-info"><p>' . __( 'All fields marked with an asterisk * are required.', 'btp_theme' ). '</p></div>';
		
				if ( $this->hasAttributes() ) {	
					$out .= '<div class="btp-shortcode-attributes">';
						foreach( $this->getAttributes() as $id => $attr ) {
							$attr->setId( $this->getName() . '_' . $attr->getId() );
							$out .= $attr->render();
						}
					$out .= '</div>';	
				}
				
				if ( $this->hasContent() ) {	
					$out .= '<div class="btp-shortcode-content">';
						$content = $this->getContent();				
						$content->setId( $this->getName() . '_' . $content->getId() );
						$out .= $content->render();
						
					$out .= '</div>';	
				}

			} else {	
				$out .= '<div class="btp-shortcode-result">';
					$out .= '<textarea>'. $this->getResult() . '</textarea>';					
				$out .= '</div>';	
			}
		
		$out .= '</div>';
		
		return $out;
	}
}


class BTP_Shortcode_Generator {	
	protected 	$_id,
				$_items;	
	
	public function __construct($id, $items = array()) {
		$this->_id = $id;
		$this->_items = $items;
		
		$this->init();
	}			
	
	/**
	 * Initialization. Loads scripts, sets hooks, etc
	 */
	public function init() {
		if ( !is_admin() )
			return;

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

		//if ( get_user_option('rich_editing') == 'true') 
		//	return;
		
		wp_enqueue_script( 
			'btp_shortcode_generator', 
			get_template_directory_uri() . '/framework/admin/js/btp_shortcode_generator.js', 
			array( 'jquery' ) 
		);
		
		
		add_filter( 'tiny_mce_version', array( &$this, 'BTP_Shortcode_Generator::increaseTinyMCEVersion' ) );
		add_filter( 'mce_external_plugins', array( &$this, 'BTP_Shortcode_Generator::registerTinyMCEPlugin') );
		
		add_filter( 'mce_buttons', array( &$this, 'addTinyMCEButton'), 0);
		add_filter( 'admin_footer', array( &$this, 'render' ) );
			   	
	}
	
	/**
	 * Increase version number to prevent caching
	 * 
	 * @param $version
	 */
	public static function increaseTinyMCEVersion( $version ) {
		return ++$version;
	}
	
	/**
	 * Adds shortcode generator button to TinyMCE visual editor
	 * 
	 * @param array $buttons
	 */
	public function addTinyMCEButton( $buttons ) {
	    array_push( $buttons, 'separator', 'separator', 'btp_shortcode_generator', 'separator' );
	    return $buttons;
	}
	
	/**
	 * Registers shortcode generator as TinyMCE plugin
	 * 
	 * @param array $plugin_array
	 */
	public static function registerTinyMCEPlugin( $plugin_array ) {    
	    $plugin_array["btp_shortcode_generator"] = get_template_directory_uri() . '/framework/admin/js/btp_shortcode_generator.js';
	    
	    return $plugin_array;
	}
	
				
	public function setId( $v ) { $this->$_id = $v; }
	public function getId() { return $this->_id; }
	
	/**
	 * Checks if shortcode generator item group is available
	 * @param string $group_id
	 */
	public function hasGroup( $group_id ) {
		return ( isset( $this->_items[ $group_id ] ) ) ? true : false; 
	}
	
	
	/**
	 * Adds shortcode generator item group 
	 * 
	 * @param string $group_id
	 * @param string $group_label
	 * @param integer $group_position
	 */
	public function addGroup($group_id, $group_label, $group_position = 0) {
		if( !isset( $this->_items[ $group_id ] ) ) {
			$this->_items[ $group_id ] = array(
				'position' 	=> $group_position,
				'label'		=> $group_label,
				'items'		=> array()
			);	
		}
	}	
	
	/**
	 * Adds shortcode generator item
	 * 
	 * @param BTP_Shortcode_Generator_Item $item
	 * @param string $group_id
	 */
	public function addItem( $item, $group_id ) {
		if( !is_admin())
			return;
		
		if( $this->hasGroup( $group_id ) ) {
			$this->addGroup( $group_id, $group_id);
		} 
				
		$this->_items[ $group_id ][ 'items' ][] = $item;
	}
	
	
	/**
	 * Renders UI
	 */
	public function render() {		
		
		$out = '';
		$out .= '<div id="' . esc_attr( $this->getId() ) . '">';
			$out .= '<div class="btp-shortcode-generator">';			
				$out .= $this->renderNav();
				$out .= $this->renderViewport();
				$out .= $this->renderActions();		
			$out .= '</div><!-- .btp-shortcode-generator -->';
		$out .= '</div>';
		
		echo $out;
	} 
	
	/**
	 * Renders UI Part - Navigation
	 */
	protected function renderNav() {
		$out = '';
		
		$out .= '<div class="btp-nav">';
			$out .= '<label for="' . esc_attr( $this->getId() . '_navigation' ) . '">' . esc_html( __( 'Select Item', 'btp_theme' ) ) . '</label>';
			$out .= '<select name="' . esc_attr( $this->getId() . '_navigation' ) . '">';	
			$out .= '<option value="" selected="selected">- - -</option>';
			
			foreach( $this->_items as $group_id => $group_def ) {
				$out .= '<optgroup label="' . esc_attr( $group_def[ 'label' ]) . '">';
			
				foreach( $group_def[ 'items' ] as $item ) {
					$out .= '<option value="' . esc_attr( $item->getName() ) . '">' . esc_html( $item->getName() ) . '</option>';
				}
				
				$out .= '</optgroup>';			
			}
	
			$out .= '</select>';

		$out .= '</div>';	
		
		return $out;
	}	
	
	/**
	 * Renders UI part - Viewport
	 */
	protected function renderViewport() {
		$out = '';	
		$out .= '<div class="btp-viewport">';
		
		foreach( $this->_items as $group_id => $group_def) {
			foreach( $group_def[ 'items' ] as $item ) {
				$out .= '<div class="btp-viewport-item">';
				$out .= $item->render();
				$out .= '</div>';
			}
		}
		
		$out .= '</div><!-- .btp-viewport -->';
		
		return $out;
	}
	
	/**
	 * Renders UI part - actions
	 */
	protected function renderActions() {
		$out = '';
		$out .= '<div class="btp-actions">';
			$out .=	'<a href="" class="button-secondary">' . __('Insert', 'btp_theme') . '</a>';
		$out .=	'</div>';
		
		return $out;
	}	
}
?>