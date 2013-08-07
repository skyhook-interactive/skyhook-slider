<?php
/**
 * Slide Advanced Custom Fields
 * @package Skyhook Slider
 * @author  Cory Crowley <cory@skyhookmarketing.com>
 * @since 	1.0.0
 */

/**
 * Register Slide Options
 * @since 1.0.0
 */
if ( function_exists( 'register_field_group' ) ) {
	register_field_group( array (
		'id'     			=> 'acf_sslider-slide-options',
		'title'  			=> 'Slide Options',
		'fields' 			=> array (
			0 => array (
				'key'          => 'field_5202811031a30',
				'label'        => 'Slide',
				'name'         => 'sslider_slide',
				'type'         => 'image',
				'instructions' => 'Attach the image corresponding to this slide.',
				'required'     => 1,
				'save_format'  => 'url',
				'preview_size' => 'full',
				'library'      => 'all',
			),
			1 => array (
				'key'           => 'field_5202813631a31',
				'label'         => 'Slide Alt',
				'name'          => 'sslider_alt',
				'type'          => 'text',
				'instructions'  => 'The alt tag of the slide. ( optional )',
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'formatting'    => 'none',
				'maxlength'     => '',
			),
		),
		'location' 		=> array (
			'rules' => array (
				0 => array (
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => Skyhook_Slider::SLIDE_POST_TYPE,
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			'allorany' => 'all'
		),
		'options' 		=> array (
			'position'       => 'normal',
			'layout'         => 'default',
			'hide_on_screen' => array (
				0  => 'the_content',
				1  => 'excerpt',
				2  => 'custom_fields',
				3  => 'discussion',
				4  => 'comments',
				5  => 'revisions',
				6  => 'slug',
				7  => 'author',
				8  => 'format',
				9  => 'featured_image',
				10 => 'categories',
				11 => 'tags',
				12 => 'send-trackbacks',
			),
		),
		'menu_order' 	=> 0,
	) );
}