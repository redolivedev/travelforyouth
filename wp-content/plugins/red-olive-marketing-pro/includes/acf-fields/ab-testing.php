<?php

// Setup for AB Testing ACF structure
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_56280f24ea3ae',
	'title' => 'On-Site Text Variation Creator',
	'fields' => array (
		array (
			'key' => 'field_57e2c93e74690',
			'label' => 'Activate Text Variation',
			'name' => 'activate_ab_testing',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array (
			'key' => 'field_56280f386a5a0',
			'label' => 'Text Variation Elements',
			'name' => 'ab_testing_elements',
			'type' => 'repeater',
			'instructions' => 'RO Marketing feature which allows the user to set up a version system for text replacement. In each version, HTML elements can be specified for text changes.<br />
<hr /><br />
Versions are specified at the end of the URL using the \'ver\' parameter.<br /><br />
<strong>Example</strong><br /><br />
www.examplesite.com?ver=alpha<br />
<hr /><br />
Identifiers are classes and IDs.<br />
A class is marked with a period: (Ex. .header).<br />
An ID is marked with a hash (Ex. #header).
<br /><br />
<strong>Example</strong><br /><br />
Version: alpha<br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;Identifier: #main-content<br />
&nbsp;&nbsp;&nbsp;&nbsp;Text: View Product<br /><br />

&nbsp;&nbsp;&nbsp;&nbsp;Identifier: .sub-content<br />
&nbsp;&nbsp;&nbsp;&nbsp;Text: Learn More<br /><br />
Version: beta<br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;Identifier: #main-content<br />
&nbsp;&nbsp;&nbsp;&nbsp;Text: Buy Now<br /><br />

&nbsp;&nbsp;&nbsp;&nbsp;Identifier: .sub-content<br />
&nbsp;&nbsp;&nbsp;&nbsp;Text: Sign Up<br /><br />',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Version',
			'sub_fields' => array (
				array (
					'key' => 'field_5628102bcacf3',
					'label' => 'Version',
					'name' => 'version',
					'type' => 'text',
					'instructions' => 'Specify the version for which the text will be displayed.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 10,
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_56e8287628c5b',
					'label' => 'Single Page',
					'name' => 'single_page',
					'type' => 'true_false',
					'instructions' => 'Select if this text variation test should appear only on this page and only once. And will not persist through other pages.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '10',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array (
					'key' => 'field_56280f886a5a1',
					'label' => 'Element and Text',
					'name' => 'element_and_text',
					'type' => 'repeater',
					'instructions' => 'Specify the text to display for this version and the element in which it will be displayed.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 80,
						'class' => '',
						'id' => '',
					),
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Add Element and Text',
					'sub_fields' => array (
						array (
							'key' => 'field_5628106ccacf4',
							'label' => 'Element Identifier',
							'name' => 'element_id',
							'type' => 'text',
							'instructions' => 'The class or ID of the element to change. #test (for an element with an ID of test) .test (for an element with a class of test).',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => 30,
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'field_56281081cacf5',
							'label' => 'Element Text',
							'name' => 'element_text',
							'type' => 'wysiwyg',
							'instructions' => 'The text for the element for this version',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => 70,
								'class' => '',
								'id' => '',
							),
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'default_value' => '',
							'delay' => 0,
						),
					),
					'collapsed' => '',
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'on-site-text-variation-creator',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;