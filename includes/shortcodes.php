<?php
/**
 * Skyhook Slider Shortcodes
 * @package Skyhook Slider
 * @author  Cory Crowley <cory@skyhookmarketing.com>
 * @since 	1.0.0
 */

/**
 * Main Skyhook Slider Shortcode
 * 
 * @param  array  $atts
 * @param  string $content
 * @return string $html
 * @since  1.0.0
 */
function skyhook_slider_shortcode( $atts, $content = '' ) {

	/* Shortcode Atts */
	extract( shortcode_atts( array(
		'number'  => -1,
		'type'    => 'regular',
		'orderby' => 'menu_order',
		'order'   => 'ASC',
	), $atts ) );
	
	/* Html */
	$html = '';
	
	/* Args */
	$args = array( 
		'post_type'      => Skyhook_Slider::SLIDE_POST_TYPE, 
		'posts_per_page' => $number, 
		'post_status'    => 'publish',
		'orderby' 			 => $orderby,
		'order'					 => $order,
	);
	
	/* The Query */
	$the_query = new WP_Query( $args );
	
	/* Start HTML */
	$html .= '<div class="flexslider">';
		$html .= '<ul class="slides">';

		/* Loop through slides */
		while ( $the_query->have_posts() ) : $the_query->the_post();

			/* Check for the slide. */
			if ( $slide = get_field( 'sslider_slide', get_the_ID() ) ) :

				/* Slide Alt */
				$slide_alt = ( get_field( 'sslider_alt', get_the_ID() ) ) ? get_field( 'sslider_alt', get_the_ID() ) : '';

				/* Output Slide */
			  $html .= '<li>';
			    $html .= '<img src="'.$slide.'" alt="'.$slide_alt.'" />';
			  $html .= '</li>';

			/* End Check */
			endif; 

		/* End Loop */
		endwhile;
		
		/* Close HTML */
		$html .= '</ul>';
	$html .= '</div>';

	/* Reset Post Data */
	wp_reset_query(); wp_reset_postdata();
	
	/* Return */
	return $html;
}
add_shortcode( 'sslider', 'skyhook_slider_shortcode' );