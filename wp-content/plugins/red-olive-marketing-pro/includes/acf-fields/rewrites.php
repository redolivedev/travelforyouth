<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_55c8df6219349',
	'title' => 'Redirects',
	'fields' => array (
		array (
			'key' => 'field_591b58881324e',
			'label' => 'Important Note',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'If you plan to add more than 50 redirects, a better option would be to put them into the .htaccess to minimize server load.',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_55c8df8825495',
			'label' => 'Redirects URLS',
			'name' => 'redirects_urls',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'min' => '',
			'max' => '',
			'layout' => 'table',
			'button_label' => 'Add Redirect',
			'collapsed' => '',
			'sub_fields' => array (
				array (
					'key' => 'field_55c8df9a25496',
					'label' => 'What to redirect',
					'name' => 'what_to_redirect',
					'type' => 'text',
					'instructions' => 'Be sure it only includes what comes after ' . $base_url . ' (e.g. to redirect ' . $base_url . '/test/ to google.com you would put /test/ here)

Also be sure to always use the trailing slash (e.g. /test should be /test/)',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '/test/',
					'prepend' => $base_url,
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_55c8dfe025497',
					'label' => 'Where to redirect to',
					'name' => 'where_to_redirect_to',
					'type' => 'text',
					'instructions' => 'Please use the full url (e.g. to redirect to google.com be sure to use https://www.google.com/)',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://www.google.com/',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
		array (
			'key' => 'field_573e427366871',
			'label' => 'Alphabetize Redirects',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'message' => 'Organize redirect alphabetically by the "What to Redirect" field.<br /><br />
<a class="acf-button button button-primary" id="alphabetize-urls">Alphabetize</a>
<div id="alpha_processing" style="display:none;">
		<h3 style="color:DodgerBlue;">Processing...</h3>
</div>
<div id="alpha_success" style="display:none;">
		<h3 style="color:green;">Alphabetization Complete!<br />Allow the page to refresh to see the updated list.</h3>
</div>
<div id="alpha_failure" style="display:none;">
		<h3 style="color:red;">Alphabetization Failed.</h3>
</div>',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_573f2bb6dc0d4',
			'label' => 'Export Redirects',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'message' => 'Export all redirects to a .csv file<br /><br />
<a class="acf-button button button-primary" target="_blank" href="/?ro_redirect_export=true">Export Redirects</a>',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_57990928daace',
			'label' => 'Delete All Redirects',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'message' => 'Delete All Redirects<br /><br />
<a class="acf-button button button-primary" id="delete-all-redirects">Delete All Redirects</a>',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_56ec64b12a87c',
			'label' => 'CSV Upload',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '<p><strong><em>CSV FILE FORMAT</em></strong></p>
<p>The format for the .csv file is fairly straigt-forward: It needs two columns. The first cell of the first column must be <strong>what_to_redirect</strong>. And the first cell of the second column must be <strong>where_to_redirect_to</strong>.</p>
<table style="width:100%" border="1">
	<tr>
		<td>what_to_redirect</td>
		<td>where_to_redirect_to</td>
	</tr>
	<tr>
		<td>/test/</td>
		<td>https://google.com</td>
	</tr>
	<tr>
		<td>/broken-link/</td>
		<td>http://www.mysite.com/working-link/</td>	
	</tr>
</table>
<div style="display:inline-block;margin-right:5px;">
		<input type="file" name="ro_redirect_file_import" id="ro_redirect_file_import">
</div>
<div style="display:inline-block;margin-right:5px;">
		<input type="checkbox" name="ro_redirect_skip_check" id="ro_redirect_skip_check">Skip URL Checking
</div>
<div id="import_processing" style="display:none;">
		<h3 style="color:DodgerBlue;">Processing...</h3>
</div>
<div id="import_success" style="display:none;">
		<h3 style="color:green;">Import Complete!<br />Allow the page to refresh to see the updated list.<br />After the refresh, make sure to check the Failed Redirects list below to see if any redirects were skipped.</h3>
</div>
<div id="import_failure" style="display:none;">
		<h3 style="color:red;">Import Failed.</h3>
</div>',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array (
			'key' => 'field_56f0665fd1168',
			'label' => 'Failed Redirects',
			'name' => 'failed_redirects',
			'type' => 'repeater',
			'instructions' => 'The redirects listed below were not added to the Redirect List because they either redirect a page which returns no errors (Code 200) or a page that is already being redirected (Code 301).<br />
<strong>To force a redirect</strong>, check the Force Redirect box. Then refresh the page to see the updated list.<br />
<strong>To delete a redirect</strong>, remove the row and click the Save Options button in the top right. ',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => '',
			'max' => '',
			'layout' => 'block',
			'button_label' => 'Cannot Add Rows',
			'sub_fields' => array (
				array (
					'key' => 'field_56f0665fd1169',
					'label' => 'What to redirect',
					'name' => 'what_to_redirect',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 50,
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '/test/',
					'prepend' => $base_url,
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_56f0665fd116a',
					'label' => 'Where to redirect to',
					'name' => 'where_to_redirect_to',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 50,
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://www.google.com/',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_56f06692d116b',
					'label' => 'Failure Reason',
					'name' => 'failure_reason',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 25,
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
					'key' => 'field_56f066bbd116c',
					'label' => 'Force Redirect',
					'name' => 'force_redirect',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 25,
						'class' => 'redirect-check',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
				),
				array (
					'key' => 'field_56f07d2af78be',
					'label' => 'Status',
					'name' => '',
					'type' => 'message',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => 50,
						'class' => '',
						'id' => '',
					),
					'message' => '<div class="force_redirect_processing" style="display:none;">
		<h3 style="color:DodgerBlue;">Processing Forced Redirect...</h3>
</div>
<div class="force_redirect_success" style="display:none;">
		<h3 style="color:green;">Forced Redirect Complete!<br />Refresh the page to update this list. </h3>
</div>
<div class="force_redirect_failure" style="display:none;">
		<h3 style="color:red;">Forced Redirect Failed.</h3>
</div>',
					'new_lines' => '',
					'esc_html' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'ro-redirects',
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