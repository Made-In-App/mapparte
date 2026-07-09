<?php

if( !class_exists( 'Xoo_Helper' ) ){
	class Xoo_Helper{

		protected $slug, $path;

		public function __construct( $slug, $path ){
			$this->slug = $slug;
			$this->path = $path;
			$this->hooks();
		}

		public function hooks(){
			add_action( 'plugins_loaded', array( $this, 'internationalize' ), 100 );
		}


		public function get_template( $template_name, $args = array(), $template_path = '', $return = false ){
			$located = $this->locate_template( $template_name, $template_path );

		    if ( $args && is_array ( $args ) ) {
		        extract ( $args );
		    }

		    if ( $return ) {
		        ob_start ();
		    }


		    // include file located
		    if ( file_exists ( $located ) ) {
		        include ( $located );
		    }

		    if ( $return ) {
		        return ob_get_clean ();
		    }
		}

		protected function locate_template( $template_name, $template_path ){
			 // Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					'templates/'.$this->slug.'/'.$template_name,
					'templates/' . $template_name,
					$template_name,
				)
			);

			//Check woocommerce directory for older version
			if( !$template && class_exists( 'woocommerce' ) ){
				if( file_exists( WC()->plugin_path() . '/templates/' . $template_name ) ){
					$template = WC()->plugin_path() . '/templates/' . $template_name;
				}
			}

		    if ( ! $template ) {
		    	if( $template_path ){
		    		$template = $template_path.'/'.$template_name;
		    		
		    	}
		    	else{
		    		$template = $this->path .'/templates/'. $template_name;
		    	}
		    }

		    return $template;
		}


		public static function get_option( $key, $subkey = '' ){
			$option = get_option( $key );
			if( $subkey ){
				if( !isset( $option[ $subkey ] ) ) return;
				return !is_array( $option[ $subkey ] ) ? esc_attr( $option[ $subkey ] ) : $option[ $subkey ];
			}
			else{
				return $option;
			}
		}


		public function internationalize(){
	        $locale = apply_filters( 'plugin_locale', get_locale(), $this->slug );
	        load_textdomain( $this->slug, WP_LANG_DIR . '/'.$this->slug.'-' . $locale . '.mo' ); //wp-content languages
	        load_plugin_textdomain( $this->slug, FALSE, basename( $this->path ) . '/languages/' ); // Plugin Languages
		}

	}

}

?>