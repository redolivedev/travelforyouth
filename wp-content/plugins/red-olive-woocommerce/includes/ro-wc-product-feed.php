<?php

namespace RoWooCommerce;

/**
 * Calls the class on the post edit screen.
 */
function call_ProductFeed() {
    new ProductFeed();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'RoWooCommerce\call_ProductFeed' );
    add_action( 'load-post-new.php', 'RoWooCommerce\call_ProductFeed' );
}

/**
 * The Class.
 */
class ProductFeed {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
    	$post_types = array('product');     //limit meta box to certain post types
    	if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'ro_wc_product_feed',
				__( 'Product Feed', 'ro_textdomain' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'advanced',
				'high'
			);
    	}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['ro_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['ro_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'ro_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */	

		// Sanitize the user input.
		update_post_meta( 
            $post_id, 
            '_ro_google_product_exclude', 
            ( isset( $_POST['_ro_google_product_exclude'] ) ? $_POST['_ro_google_product_exclude'] : '' ) 
        );
		update_post_meta( $post_id, '_ro_google_product_title', sanitize_text_field( $_POST['_ro_google_product_title'] ) );
		update_post_meta($post_id, '_ro_google_product_description', sanitize_text_field($_POST['_ro_google_product_description']));
		update_post_meta( $post_id, '_ro_google_product_cat', sanitize_text_field( $_POST['_ro_google_product_cat'] ) );
		update_post_meta( $post_id, '_ro_google_product_brand', sanitize_text_field( $_POST['_ro_google_product_brand'] ) );
		update_post_meta( $post_id, '_ro_google_product_type', sanitize_text_field( $_POST['_ro_google_product_type'] ) );
		update_post_meta( $post_id, '_ro_google_product_condition', $_POST['_ro_google_product_condition'] );
		update_post_meta( $post_id, '_ro_google_product_age_group', $_POST['_ro_google_product_age_group'] );
		update_post_meta( $post_id, '_ro_google_product_gender', $_POST['_ro_google_product_gender'] );
		update_post_meta( $post_id, '_ro_google_product_size_type', $_POST['_ro_google_product_size_type'] );
		update_post_meta( $post_id, '_ro_google_product_size', $_POST['_ro_google_product_size'] );
		update_post_meta( $post_id, '_ro_google_product_color', $_POST['_ro_google_product_color'] );
		update_post_meta( $post_id, '_ro_google_product_gtin', $_POST['_ro_google_product_gtin'] );
		update_post_meta( $post_id, '_ro_google_product_cust_lbl_1', sanitize_text_field( $_POST['_ro_google_product_cust_lbl_1'] ) );
		update_post_meta( $post_id, '_ro_google_product_cust_lbl_2', sanitize_text_field( $_POST['_ro_google_product_cust_lbl_2'] ) );
		update_post_meta( $post_id, '_ro_google_product_cust_lbl_3', sanitize_text_field( $_POST['_ro_google_product_cust_lbl_3'] ) );
		update_post_meta( $post_id, '_ro_google_product_cust_lbl_4', sanitize_text_field( $_POST['_ro_google_product_cust_lbl_4'] ) );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'ro_inner_custom_box', 'ro_inner_custom_box_nonce' );

		echo "<table cellpadding='5' style='width:100%'>";
		// Display the form, using the current value.

		$ro_exclude = get_post_meta( $post->ID, '_ro_google_product_exclude', true );
		echo '<tr><td style="width:15%;"><label for="_ro_google_product_exclude">';
		_e( '<strong>Exclude Product</strong> <br />Exclude product from Google feed', 'ro_textdomain' );
		echo '</label></td>';
        echo '<td style="width:80%;"><input type="checkbox" id="_ro_google_product_exclude" name="_ro_google_product_exclude" ' . ($ro_exclude ? 'checked' : '') . '/></tr>';
        
        $ro_product_gtin = get_post_meta( $post->ID, '_ro_google_product_gtin', true );
		echo '<tr><td><label for="_ro_google_product_gtin">';
		_e( 'Google Product <strong>GTIN</strong>', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_gtin" name="_ro_google_product_gtin"';
		echo ' value="' . esc_attr( $ro_product_gtin ) . '" /></td></tr>';

		$ro_title = get_post_meta( $post->ID, '_ro_google_product_title', true );
		echo '<tr><td style="width:15%;"><label for="_ro_google_product_title">';
		_e( 'Google Product <strong>Title</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td style="width:80%;"><input maxlength="150" style="width:100%" type="text" id="_ro_google_product_title" name="_ro_google_product_title"';
		echo ' value="' . esc_attr( $ro_title ) . '" /><td style="width:5%;"><span class="title-counter">0</span>/150</td></td></tr>';

		$ro_description = get_post_meta( $post->ID, '_ro_google_product_description', true );
		echo '<tr><td><label for="_ro_google_product_description">';
		_e( 'Google Product <strong>Description</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><textarea maxlength="4999" style="width:100%" id="_ro_google_product_description" name="_ro_google_product_description">';
		echo esc_attr( $ro_description ) . '</textarea></td><td><span class="desc-counter">0</span>/4999</td></tr>';

		$ro_brand = get_post_meta( $post->ID, '_ro_google_product_brand', true );
		echo '<tr><td><label for="_ro_google_product_brand">';
		_e( 'Google Product <strong>Brand</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_brand" name="_ro_google_product_brand"';
        echo ' value="' . esc_attr( $ro_brand ) . '" /></td></tr>';
        
        $ro_cat = get_post_meta( $post->ID, '_ro_google_product_cat', true );
		echo '<tr><td style="width:20%"><label for="_ro_google_product_cat">';
		_e( 'Google Product <strong>Category</strong> <br />You can find that <a href="http://www.google.com/basepages/producttype/taxonomy.en-US.txt" target="_blank">HERE</a>', 'ro_textdomain' );
		echo '</label></td><td>';
		echo '<input style="width:100%" type="text" id="_ro_google_product_cat" name="_ro_google_product_cat"';
        echo ' value="' . esc_attr( $ro_cat ) . '" /></td></tr>';

		$ro_product_type = get_post_meta( $post->ID, '_ro_google_product_type', true );
		echo '<tr><td><label for="_ro_google_product_type">';
		_e( 'Google Product <strong>Type</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_type" name="_ro_google_product_type"';
		echo ' value="' . esc_attr( $ro_product_type ) . '" /></td></tr>';

		$ro_product_cond = get_post_meta( $post->ID, '_ro_google_product_condition', true );
		echo '<tr><td><label for="_ro_google_product_condition">';
		_e( 'Google Product <strong>Condition</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '
			<td>
				<select style="width:100%" id="_ro_google_product_condition" name="_ro_google_product_condition">
					<option value="">Use Default</option>
					<option value="new"' . ($ro_product_cond && $ro_product_cond == 'new' ? 'selected' : '') . 
						'>New</option>
					<option value="refurbished"' 
						. ($ro_product_cond && $ro_product_cond == 'refurbished' ? 'selected' : '') . 
						'>Refurbished</option>
					<option value="used"' . ($ro_product_cond && $ro_product_cond == 'used' ? 'selected' : '') . 
						'>Used</option>
				</select>
			</td>
        </tr>';
        
        $ro_product_age_group = get_post_meta( $post->ID, '_ro_google_product_age_group', true );
		echo '<tr><td><label for="_ro_google_product_age_group">';
		_e( 'Google Product <strong>Age Group</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '
			<td>
				<select style="width:100%" id="_ro_google_product_age_group" name="_ro_google_product_age_group">
					<option value="">Use Default</option>
					<option value="adult"' . ($ro_product_age_group && $ro_product_age_group == 'adult' ? 'selected' : '') . 
						'>Adult</option>
					<option value="kids"' 
						. ($ro_product_age_group && $ro_product_age_group == 'kids' ? 'selected' : '') . 
						'>Kids</option>
                    <option value="toddler"' . ($ro_product_age_group && $ro_product_age_group == 'toddler' ? 'selected' : '') . '>Toddler</option>
                    <option value="infant"' . ($ro_product_age_group && $ro_product_age_group == 'infant' ? 'selected' : '') . '>Infant</option>
                    <option value="newborn"' . ($ro_product_age_group && $ro_product_age_group == 'newborn' ? 'selected' : '') . '>Newborn</option>
				</select>
			</td>
		</tr>';

		$ro_product_gender = get_post_meta( $post->ID, '_ro_google_product_gender', true );
		echo '<tr><td><label for="_ro_google_product_gender">';
		_e( 'Google Product <strong>Gender</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '
			<td>
				<select style="width:100%" id="_ro_google_product_gender" name="_ro_google_product_gender">
					<option value="">Use Default</option>
					<option value="male"' . ($ro_product_gender && $ro_product_gender == 'male' ? 'selected' : '') . 
						'>Male</option>
					<option value="female"' 
						. ($ro_product_gender && $ro_product_gender == 'female' ? 'selected' : '') . 
						'>Female</option>
					<option value="unisex"' . ($ro_product_gender && $ro_product_gender == 'unisex' ? 'selected' : '') . '>Unisex</option>
				</select>
			</td>
		</tr>';

		$ro_product_size_type = get_post_meta( $post->ID, '_ro_google_product_size_type', true );
		echo '<tr><td><label for="_ro_google_product_size_type">';
		_e( 'Google Product <strong>Size Type</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '
			<td>
				<select style="width:100%" id="_ro_google_product_size_type" name="_ro_google_product_size_type">
					<option value="">Use Default</option>
					<option value="regular"' . ($ro_product_size_type && $ro_product_size_type == 'regular' ? 'selected' : '') . '>Regular</option>
					<option value="petite"' 
						. ($ro_product_size_type && $ro_product_size_type == 'female' ? 'selected' : '') . 
						'>Petite</option>
					<option value="plus"' . ($ro_product_size_type && $ro_product_size_type == 'plus' ? 'selected' : '') . '>Plus</option>
					<option value="bigandtall"' . ($ro_product_size_type && $ro_product_size_type == 'bigandtall' ? 'selected' : '') . '>Big and Tall</option>
					<option value="maternity"' . ($ro_product_size_type && $ro_product_size_type == 'maternity' ? 'selected' : '') . '>Maternity</option>
				</select>
			</td>
        </tr>';

        $ro_product_size = get_post_meta( $post->ID, '_ro_google_product_size', true );
		echo '<tr><td><label for="_ro_google_product_size">';
		_e( 'Google Product <strong>Size</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_size" name="_ro_google_product_size"';
		echo ' value="' . esc_attr( $ro_product_size ) . '" /></td></tr>';

        $ro_product_color = get_post_meta( $post->ID, '_ro_google_product_color', true );
		echo '<tr><td><label for="_ro_google_product_color">';
		_e( 'Google Product <strong>Color</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_color" name="_ro_google_product_color"';
		echo ' value="' . esc_attr( $ro_product_color ) . '" /></td></tr>';
        
		echo '<tr><td><label for="custom_products_note"><strong>Custom Labels Note: </strong></label></td>';
		echo '<td>To use the Custom Labels below, make sure that the Custom Labels checkbox is set in the <strong>RO-WooCommerce global settings.</strong></td></tr>';

		$ro_product_cust_lbl_1 = get_post_meta( $post->ID, '_ro_google_product_cust_lbl_1', true );
		echo '<tr><td><label for="_ro_google_product_cust_lbl_1">';
		_e( 'Google Product <strong>Custom Label 1</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_cust_lbl_1" name="_ro_google_product_cust_lbl_1"';
		echo ' value="' . esc_attr( $ro_product_cust_lbl_1 ) . '" /></td></tr>';

		$ro_product_cust_lbl_2 = get_post_meta( $post->ID, '_ro_google_product_cust_lbl_2', true );
		echo '<tr><td><label for="_ro_google_product_cust_lbl_2">';
		_e( 'Google Product <strong>Custom Label 2</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_cust_lbl_2" name="_ro_google_product_cust_lbl_2"';
		echo ' value="' . esc_attr( $ro_product_cust_lbl_2 ) . '" /></td></tr>';

		$ro_product_cust_lbl_3 = get_post_meta( $post->ID, '_ro_google_product_cust_lbl_3', true );
		echo '<tr><td><label for="_ro_google_product_cust_lbl_3">';
		_e( 'Google Product <strong>Custom Label 3</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_cust_lbl_3" name="_ro_google_product_cust_lbl_3"';
		echo ' value="' . esc_attr( $ro_product_cust_lbl_3 ) . '" /></td></tr>';

		$ro_product_cust_lbl_4 = get_post_meta( $post->ID, '_ro_google_product_cust_lbl_4', true );
		echo '<tr><td><label for="_ro_google_product_cust_lbl_4">';
		_e( 'Google Product <strong>Custom Label 4</strong> <br />Leave it blank to use the default', 'ro_textdomain' );
		echo '</label></td>';
		echo '<td><input style="width:100%" type="text" id="_ro_google_product_cust_lbl_4" name="_ro_google_product_cust_lbl_4"';
		echo ' value="' . esc_attr( $ro_product_cust_lbl_4 ) . '" /></td></tr>';

		echo "</table>";

	}
}
