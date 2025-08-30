<?php
/**
 * CoachPress Lite Customizer Typography Control
 *
 * @package CoachPress_Lite
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'CoachPress_Lite_Typography_Control' ) ) {
    
    class CoachPress_Lite_Typography_Control extends WP_Customize_Control {
    
    	public $tooltip = '';
    	public $js_vars = array();
    	public $output = array();
    	public $option_type = 'theme_mod';
    	public $type = 'coachpress-lite-typography';
    
    	/**
    	 * Refresh the parameters passed to the JavaScript via JSON.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function to_json() {
    		parent::to_json();
    
    		if ( isset( $this->default ) ) {
    			$this->json['default'] = $this->default;
    		} else {
    			$this->json['default'] = $this->setting->default;
    		}
    		$this->json['js_vars'] = $this->js_vars;
    		$this->json['output']  = $this->output;
    		$this->json['value']   = $this->value();
    		$this->json['choices'] = $this->choices;
    		$this->json['link']    = $this->get_link();
    		$this->json['tooltip'] = $this->tooltip;
    		$this->json['id']      = $this->id;
    		$this->json['l10n']    = apply_filters( 'coachpress_lite_il8n_strings', array(
    			'on'                 => esc_attr__( 'ON', 'coachpress-lite' ),
    			'off'                => esc_attr__( 'OFF', 'coachpress-lite' ),
    			'all'                => esc_attr__( 'All', 'coachpress-lite' ),
    			'cyrillic'           => esc_attr__( 'Cyrillic', 'coachpress-lite' ),
    			'cyrillic-ext'       => esc_attr__( 'Cyrillic Extended', 'coachpress-lite' ),
    			'devanagari'         => esc_attr__( 'Devanagari', 'coachpress-lite' ),
    			'greek'              => esc_attr__( 'Greek', 'coachpress-lite' ),
    			'greek-ext'          => esc_attr__( 'Greek Extended', 'coachpress-lite' ),
    			'khmer'              => esc_attr__( 'Khmer', 'coachpress-lite' ),
    			'latin'              => esc_attr__( 'Latin', 'coachpress-lite' ),
    			'latin-ext'          => esc_attr__( 'Latin Extended', 'coachpress-lite' ),
    			'vietnamese'         => esc_attr__( 'Vietnamese', 'coachpress-lite' ),
    			'hebrew'             => esc_attr__( 'Hebrew', 'coachpress-lite' ),
    			'arabic'             => esc_attr__( 'Arabic', 'coachpress-lite' ),
    			'bengali'            => esc_attr__( 'Bengali', 'coachpress-lite' ),
    			'gujarati'           => esc_attr__( 'Gujarati', 'coachpress-lite' ),
    			'tamil'              => esc_attr__( 'Tamil', 'coachpress-lite' ),
    			'telugu'             => esc_attr__( 'Telugu', 'coachpress-lite' ),
    			'thai'               => esc_attr__( 'Thai', 'coachpress-lite' ),
    			'serif'              => _x( 'Serif', 'font style', 'coachpress-lite' ),
    			'sans-serif'         => _x( 'Sans Serif', 'font style', 'coachpress-lite' ),
    			'monospace'          => _x( 'Monospace', 'font style', 'coachpress-lite' ),
    			'font-family'        => esc_attr__( 'Font Family', 'coachpress-lite' ),
    			'font-size'          => esc_attr__( 'Font Size', 'coachpress-lite' ),
    			'font-weight'        => esc_attr__( 'Font Weight', 'coachpress-lite' ),
    			'line-height'        => esc_attr__( 'Line Height', 'coachpress-lite' ),
    			'font-style'         => esc_attr__( 'Font Style', 'coachpress-lite' ),
    			'letter-spacing'     => esc_attr__( 'Letter Spacing', 'coachpress-lite' ),
    			'text-align'         => esc_attr__( 'Text Align', 'coachpress-lite' ),
    			'text-transform'     => esc_attr__( 'Text Transform', 'coachpress-lite' ),
    			'none'               => esc_attr__( 'None', 'coachpress-lite' ),
    			'uppercase'          => esc_attr__( 'Uppercase', 'coachpress-lite' ),
    			'lowercase'          => esc_attr__( 'Lowercase', 'coachpress-lite' ),
    			'top'                => esc_attr__( 'Top', 'coachpress-lite' ),
    			'bottom'             => esc_attr__( 'Bottom', 'coachpress-lite' ),
    			'left'               => esc_attr__( 'Left', 'coachpress-lite' ),
    			'right'              => esc_attr__( 'Right', 'coachpress-lite' ),
    			'center'             => esc_attr__( 'Center', 'coachpress-lite' ),
    			'justify'            => esc_attr__( 'Justify', 'coachpress-lite' ),
    			'color'              => esc_attr__( 'Color', 'coachpress-lite' ),
    			'select-font-family' => esc_attr__( 'Select a font-family', 'coachpress-lite' ),
    			'variant'            => esc_attr__( 'Variant', 'coachpress-lite' ),
    			'style'              => esc_attr__( 'Style', 'coachpress-lite' ),
    			'size'               => esc_attr__( 'Size', 'coachpress-lite' ),
    			'height'             => esc_attr__( 'Height', 'coachpress-lite' ),
    			'spacing'            => esc_attr__( 'Spacing', 'coachpress-lite' ),
    			'ultra-light'        => esc_attr__( 'Ultra-Light 100', 'coachpress-lite' ),
    			'ultra-light-italic' => esc_attr__( 'Ultra-Light 100 Italic', 'coachpress-lite' ),
    			'light'              => esc_attr__( 'Light 200', 'coachpress-lite' ),
    			'light-italic'       => esc_attr__( 'Light 200 Italic', 'coachpress-lite' ),
    			'book'               => esc_attr__( 'Book 300', 'coachpress-lite' ),
    			'book-italic'        => esc_attr__( 'Book 300 Italic', 'coachpress-lite' ),
    			'regular'            => esc_attr__( 'Normal 400', 'coachpress-lite' ),
    			'italic'             => esc_attr__( 'Normal 400 Italic', 'coachpress-lite' ),
    			'medium'             => esc_attr__( 'Medium 500', 'coachpress-lite' ),
    			'medium-italic'      => esc_attr__( 'Medium 500 Italic', 'coachpress-lite' ),
    			'semi-bold'          => esc_attr__( 'Semi-Bold 600', 'coachpress-lite' ),
    			'semi-bold-italic'   => esc_attr__( 'Semi-Bold 600 Italic', 'coachpress-lite' ),
    			'bold'               => esc_attr__( 'Bold 700', 'coachpress-lite' ),
    			'bold-italic'        => esc_attr__( 'Bold 700 Italic', 'coachpress-lite' ),
    			'extra-bold'         => esc_attr__( 'Extra-Bold 800', 'coachpress-lite' ),
    			'extra-bold-italic'  => esc_attr__( 'Extra-Bold 800 Italic', 'coachpress-lite' ),
    			'ultra-bold'         => esc_attr__( 'Ultra-Bold 900', 'coachpress-lite' ),
    			'ultra-bold-italic'  => esc_attr__( 'Ultra-Bold 900 Italic', 'coachpress-lite' ),
    			'invalid-value'      => esc_attr__( 'Invalid Value', 'coachpress-lite' ),
    		) );
    
    		$defaults = array( 'font-family'=> false );
    
    		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );
    	}
    
    	/**
    	 * Enqueue scripts and styles.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function enqueue() {
    		wp_enqueue_style( 'coachpress-lite-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.css', null );
            
            wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-tooltip' );
    		wp_enqueue_script( 'jquery-stepper-min-js' );
    		wp_enqueue_script( 'coachpress-lite-selectize', get_template_directory_uri() . '/inc/js/selectize.js', array( 'jquery' ), false, true );
    		wp_enqueue_script( 'coachpress-lite-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.js', array( 'jquery', 'coachpress-lite-selectize' ), false, true );
    
    		$google_fonts   = CoachPress_Lite_Fonts::get_google_fonts();
    		$standard_fonts = CoachPress_Lite_Fonts::get_standard_fonts();
    		$all_variants   = CoachPress_Lite_Fonts::get_all_variants();
    
    		$standard_fonts_final = array();
    		foreach ( $standard_fonts as $key => $value ) {
    			$standard_fonts_final[] = array(
    				'family'      => $value['stack'],
    				'label'       => $value['label'],
    				'is_standard' => true,
    				'variants'    => array(
    					array(
    						'id'    => 'regular',
    						'label' => $all_variants['regular'],
    					),
    					array(
    						'id'    => 'italic',
    						'label' => $all_variants['italic'],
    					),
    					array(
    						'id'    => '700',
    						'label' => $all_variants['700'],
    					),
    					array(
    						'id'    => '700italic',
    						'label' => $all_variants['700italic'],
    					),
    				),
    			);
    		}
    
    		$google_fonts_final = array();
    
    		if ( is_array( $google_fonts ) ) {
    			foreach ( $google_fonts as $family => $args ) {
    				$label    = ( isset( $args['label'] ) ) ? $args['label'] : $family;
    				$variants = ( isset( $args['variants'] ) ) ? $args['variants'] : array( 'regular', '700' );
    
    				$available_variants = array();
    				foreach ( $variants as $variant ) {
    					if ( array_key_exists( $variant, $all_variants ) ) {
    						$available_variants[] = array( 'id' => $variant, 'label' => $all_variants[ $variant ] );
    					}
    				}
    
    				$google_fonts_final[] = array(
    					'family'   => $family,
    					'label'    => $label,
    					'variants' => $available_variants
    				);
    			}
    		}
    
    		$final = array_merge( $standard_fonts_final, $google_fonts_final );
    		wp_localize_script( 'coachpress-lite-typography', 'all_fonts', $final );
    	}
    
    	/**
    	 * An Underscore (JS) template for this control's content (but not its container).
    	 *
    	 * Class variables for this control class are available in the `data` JS object;
    	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
    	 *
    	 * I put this in a separate file because PhpStorm didn't like it and it fucked with my formatting.
    	 *
    	 * @see    WP_Customize_Control::print_template()
    	 *
    	 * @access protected
    	 * @return void
    	 */
    	protected function content_template(){ ?>
    		<# if ( data.tooltip ) { #>
                <a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
            <# } #>
            
            <label class="customizer-text">
                <# if ( data.label ) { #>
                    <span class="customize-control-title">{{{ data.label }}}</span>
                <# } #>
                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
            </label>
            
            <div class="wrapper">
                <# if ( data.default['font-family'] ) { #>
                    <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
                    <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>
                    <div class="font-family">
                        <h5>{{ data.l10n['font-family'] }}</h5>
                        <select id="coachpress-lite-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
                    </div>
                    <div class="variant coachpress-lite-variant-wrapper">
                        <h5>{{ data.l10n['style'] }}</h5>
                        <select class="variant" id="coachpress-lite-typography-variant-{{{ data.id }}}"></select>
                    </div>
                <# } #>   
                
            </div>
            <?php
    	}    

        protected function render_content(){
        }
    }
}