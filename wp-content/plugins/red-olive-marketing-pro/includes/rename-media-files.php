<?php

namespace RoMarketingPro;

class RenameMediaFiles{

	function __construct(){
		$this->init();
		$this->init_actions();
	}

	function init(){
		add_action( 'add_meta_boxes', array( $this, 'add_rename_metabox' ) );
		add_filter( 'attachment_fields_to_save', array( $this, 'attachment_save' ), 20, 2 );
	}

	//Log function for debugging
	function log( $data, $inErrorLog = false ) {

		return; //Remove this line to allow log to be written to

		if ( $inErrorLog )
			error_log( $data );

		$fh = fopen( trailingslashit( WP_PLUGIN_DIR ) . 'red-olive-marketing/red-olive-marketing.log', 'a' );
		$date = date( "Y-m-d H:i:s" );
		fwrite( $fh, "$date: {$data}\n" );
		fclose( $fh );
	}

	function add_rename_metabox() {
		add_meta_box( 'ro_rename_media', 'Rename File', array( $this, 'ro_add_rename_field' ), 'attachment', 'side', 'high' );
	}

	function ro_add_rename_field( $post ) {
		$info = pathinfo( get_attached_file( $post->ID ) );
		$basename = $info['basename'];
		echo '<input type="text" class="widefat" name="ro_new_filename" value="' . $basename. '" />';
		echo '<p class="description">Enter the new filename</p>';

		return $post;
	}

	function attachment_save( $post, $attachment ) {
		$info = pathinfo( get_attached_file( $post['ID'] ) );
		$basename = $info['basename'];
		$new = $post['ro_new_filename'];

		if ( !empty( $new ) && $basename !== $new )
			return $this->rename_media( $post, $attachment, false, $new );

		return $post;
	}

	function rename_media( $post, $attachment, $disableMediaLibraryMode = false, $forceFilename = null ) {
		$force = !empty( $forceFilename );

		// MEDIA TITLE & FILE PARTS
		$meta = wp_get_attachment_metadata( $post['ID'] );
		$old_filepath = get_attached_file( $post['ID'] ); // '2011/01/whatever.jpeg'
		$path_parts = pathinfo( $old_filepath );
		$directory = $path_parts['dirname']; // '2011/01'
		$old_filename = $path_parts['basename']; // 'whatever.jpeg'
		$old_ext = $path_parts['extension'];
		$base_new_title = $post['post_title'];
		$ext = $path_parts['extension'];

		$this->log( "** Rename Media: " . $old_filename );

		if ( !$this->is_real_media( $post['ID'] ) ) {
			$this->log( "Attachment {$post['ID']} looks like a translation, better not to continue." );
			return $post;
		}

		// Is this a header image? If so, skip it
		if ( $this->is_header_image( $post['ID'] ) ) {
			$this->log( "Doesn't rename header image." );
			return $post;
		}

		$sanitized_media_title = $this->new_filename( $post, $base_new_title, $forceFilename );
		$this->log( "New file should be: " . $sanitized_media_title );

		// Don't do anything if the media title didn't change or if it would turn to an empty string
		if ( $path_parts['basename'] == $sanitized_media_title ) {
			$this->log( "File seems renamed already." );
			return $post;
		}

		// MEDIA LIBRARY USAGE DETECTION
		// Detects if the user is using the Media Library or 'Add an Image' (while a post edit)
		// If it is not the Media Library, we don't rename, to avoid issues
		$media_library_mode = !isset( $attachment['image-size'] ) || $disableMediaLibraryMode;
		if ( !$media_library_mode ) {
			// This media requires renaming
			if ( !get_post_meta( $post['ID'], '_require_file_renaming' ) )
				add_post_meta( $post['ID'], '_require_file_renaming', true, true );
			$this->log( "Seems like the user is editing a post. Marked the file as to be renamed." );
			return $post;
		}

		// NEW DESTINATION FILES ALREADY EXISTS - DON'T DO ANYTHING
		$force_rename = false;
		$new_filepath = trailingslashit( $directory ) . $sanitized_media_title;
		if ( !$force_rename && file_exists( $directory . "/" . $sanitized_media_title ) ) {
			if ( !get_post_meta( $post['ID'], '_require_file_renaming' ) )
				add_post_meta( $post['ID'], '_require_file_renaming', true, true );
			$this->log( "The new file ($new_filepath) already exists, it is safer to avoid doing anything." );
			return $post;
		}

		// Exact same code as rename-media, it's a good idea to keep track of the original filename.
		$original_filename = get_post_meta( $post['ID'], '_original_filename', true );
		if ( empty( $original_filename ) )
			add_post_meta( $post['ID'], '_original_filename', $old_filename, true );

		// Rename the main media file.
		try {
			if ( ( !file_exists( $old_filepath ) || !rename( $old_filepath, $new_filepath ) ) && !$force_rename ) {
				$this->log( "The file couldn't be renamed from $old_filepath to $new_filepath." );
				return $post;
			}
			$this->log( "File $old_filepath renamed to $new_filepath." );
			do_action( 'ro_path_renamed', $post, $old_filepath, $new_filepath );
		}
		catch (Exception $e) {
			return $post;
		}

		// Filenames without extensions
		$noext_old_filename = $this->str_replace( '.' . $old_ext, '', $old_filename );
		$noext_new_filename = $this->str_replace( '.' . $ext, '', $sanitized_media_title );

		// Update the attachment meta
		if ( $meta ) {
			$meta['file'] = $this->str_replace( $noext_old_filename, $noext_new_filename, $meta['file'] );
			if ( isset( $meta["url"] ) && $meta["url"] != "" && count( $meta["url"] ) > 4 )
				$meta["url"] = $this->str_replace( $noext_old_filename, $noext_new_filename, $meta["url"] );
			else
				$meta["url"] = $noext_new_filename . "." . $ext;
		}

		// Images
		if ( wp_attachment_is_image( $post['ID'] ) ) {
			// Loop through the different sizes in the case of an image, and rename them.
			$orig_image_urls = array();
			$orig_image_data = wp_get_attachment_image_src( $post['ID'], 'full' );
			$orig_image_urls['full'] = $orig_image_data[0];
			if ( empty( $meta['sizes'] ) ) {
				$this->log( "The WP metadata for attachment " . $post['ID'] . " does not exist.", true );
			}
			else {
				foreach ( $meta['sizes'] as $size => $meta_size ) {
					$meta_old_filename = $meta['sizes'][$size]['file'];
					$meta_old_filepath = trailingslashit( $directory ) . $meta_old_filename;
					$meta_new_filename = $this->str_replace( $noext_old_filename, $noext_new_filename, $meta_old_filename );
					$meta_new_filepath = trailingslashit( $directory ) . $meta_new_filename;
					$orig_image_data = wp_get_attachment_image_src( $post['ID'], $size );
					$orig_image_urls[$size] = $orig_image_data[0];
					// ak: Double check files exist before trying to rename.
					if ( $force_rename || ( file_exists( $meta_old_filepath ) && ( ( !file_exists( $meta_new_filepath ) )
						|| is_writable( $meta_new_filepath ) ) ) ) {
						// WP Retina 2x is detected, let's rename those files as well
						if ( function_exists( 'wr2x_generate_images' ) ) {
							$wr2x_old_filepath = $this->str_replace( '.' . $ext, '@2x.' . $ext, $meta_old_filepath );
							$wr2x_new_filepath = $this->str_replace( '.' . $ext, '@2x.' . $ext, $meta_new_filepath );
							if ( file_exists( $wr2x_old_filepath ) && ( (!file_exists( $wr2x_new_filepath ) ) || is_writable( $wr2x_new_filepath ) ) ) {
								@rename( $wr2x_old_filepath, $wr2x_new_filepath );
								$this->log( "Retina file $wr2x_old_filepath renamed to $wr2x_new_filepath." );
								do_action( 'ro_path_renamed', $post, $wr2x_old_filepath, $wr2x_new_filepath );
							}
						}
						@rename( $meta_old_filepath, $meta_new_filepath );
						$meta['sizes'][$size]['file'] = $meta_new_filename;
						$this->log( "File $meta_old_filepath renamed to $meta_new_filepath." );
						do_action( 'ro_path_renamed', $post, $meta_old_filepath, $meta_new_filepath );
					}
				}
			}
		}
		else {
			$orig_attachment_url = wp_get_attachment_url( $post['ID'] );
		}

		// This media doesn't require renaming anymore
		delete_post_meta( $post['ID'], '_require_file_renaming' );
		if ( $force ) {
			add_post_meta( $post['ID'], '_manual_file_renaming', true, true );
		}

		// Update metadata
		if ( $meta )
			wp_update_attachment_metadata( $post['ID'], $meta );
		update_attached_file( $post['ID'], $new_filepath );
		clean_post_cache( $post['ID'] );

		// Call the actions so that the plugin's hooks can update everything else (other than the files)
		if ( wp_attachment_is_image( $post['ID'] ) ) {
			$orig_image_url = $orig_image_urls['full'];
			$new_image_data = wp_get_attachment_image_src( $post['ID'], 'full' );
			$new_image_url = $new_image_data[0];
			do_action( 'ro_url_renamed', $post, $orig_image_url, $new_image_url );
			if ( !empty( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $size => $meta_size ) {
					$orig_image_url = $orig_image_urls[$size];
					$new_image_data = wp_get_attachment_image_src( $post['ID'], $size );
					$new_image_url = $new_image_data[0];
					do_action( 'ro_url_renamed', $post, $orig_image_url, $new_image_url );
				}
			}
		}
		else {
			$new_attachment_url = wp_get_attachment_url( $post['ID'] );
			do_action( 'ro_url_renamed', $post, $orig_attachment_url, $new_attachment_url );
		}

		// HTTP REFERER set to the new media link
		if ( isset( $_REQUEST['_wp_original_http_referer'] ) && strpos( $_REQUEST['_wp_original_http_referer'], '/wp-admin/' ) === false ) {
			$_REQUEST['_wp_original_http_referer'] = get_permalink( $post['ID'] );
		}

		do_action( 'ro_media_renamed', $post, $old_filepath, $new_filepath );
		return $post;
	}

	function wpml_media_is_installed() {
		return defined( 'WPML_MEDIA_VERSION' );
	}

	function is_real_media( $id ) {
		if ( $this->wpml_media_is_installed() ) {
			global $sitepress;
			$language = $sitepress->get_default_language( $id );
			return icl_object_id( $id, 'attachment', true, $language ) == $id;
		}
		return true;
	}

	function is_header_image( $id ) {
		static $headers = false;
		if ( $headers == false ) {
			global $wpdb;
			$headers = $wpdb->get_col( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attachment_is_custom_header'" );
		}
		return in_array( $id, $headers );
	}

	function new_filename( $media, $title, $forceFilename = null ) {
		if ( $forceFilename )
			$forceFilename = preg_replace( '/\\.[^.\\s]{3,4}$/', '', trim( $forceFilename ) );
		$force = !empty( $forceFilename );
		$old_filepath = get_attached_file( $media['ID'] );
		$path_parts = pathinfo( $old_filepath );
		$old_filename = $path_parts['basename'];
		$ext = $path_parts['extension'];

		if ( $force ){
			$sanitized_media_title = $forceFilename;
		}
		else {
			$utf8_filename = $this->getoption( 'utf8_filename', 'mfrh_basics', null );
			$sanitized_media_title = $utf8_filename ? sanitize_file_name( $title ) :
				str_replace( "%", "-", sanitize_title( $title ) );
		}
		if ( empty( $sanitized_media_title ) )
			$sanitized_media_title = "empty";
		$sanitized_media_title = $sanitized_media_title . '.' . $ext;
		if ( !$forceFilename )
			$sanitized_media_title = apply_filters( 'ro_new_filename', $sanitized_media_title, $old_filename, $media );
		return $sanitized_media_title;
	}

	// Only replace the first occurence
	function str_replace( $needle, $replace, $haystack ) {
		$pos = strpos( $haystack, $needle );
		if ( $pos !== false ) {
		    $haystack = substr_replace( $haystack, $replace, $pos, strlen( $needle ) );
		}
		return $haystack;
	}

	function init_actions() {
		add_action( 'ro_url_renamed', array( $this, 'action_update_posts' ), 10, 3 );
		add_action( 'ro_url_renamed', array( $this, 'action_update_postmeta' ), 10, 3 );
		add_action( 'ro_media_renamed', array( $this, 'action_update_slug' ), 10, 3 );
		add_action( 'ro_media_renamed', array( $this, 'action_sync_alt' ), 10, 3 );
	}

	// Mass update of all the articles with the new filenames
	function action_update_posts( $post, $orig_image_url, $new_image_url ) {
		global $wpdb;
		$query = $wpdb->prepare( "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '%s', '%s');", $orig_image_url, $new_image_url );
		$query_revert = $wpdb->prepare( "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '%s', '%s');", $new_image_url, $orig_image_url );
		$wpdb->query( $query );
		$this->log( "Post content like $orig_image_url were replaced by $new_image_url." );
	}

	// Mass update of all the meta with the new filenames
	function action_update_postmeta( $post, $orig_image_url, $new_image_url ) {
		global $wpdb;
		$query = $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = '%s' WHERE meta_key <> '_original_filename' AND (TRIM(meta_value) = '%s' OR TRIM(meta_value) = '%s');", $new_image_url, $orig_image_url, str_replace( ' ', '%20', $orig_image_url ) );
		$query_revert = $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = '%s' WHERE meta_key <> '_original_filename' AND meta_value = '%s';", $orig_image_url, $new_image_url );
		$wpdb->query( $query );
		$this->log( "Metadata exactly like $orig_image_url were replaced by $new_image_url." );
	}

	// Slug update
	function action_update_slug( $post, $old_filepath, $new_filepath ) {
		$oldslug = $post['post_name'];
		$info = pathinfo( $new_filepath );
		$newslug = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $info['basename'] );
		global $wpdb;
		$query = $wpdb->prepare( "UPDATE $wpdb->posts SET post_name = '%s' WHERE ID = '%d'", $newslug,  $post['ID'] );
		$query_revert = $wpdb->prepare( "UPDATE $wpdb->posts SET post_name = '%s' WHERE ID = '%d'", $oldslug,  $post['ID'] );
		$wpdb->query( $query );
		clean_post_cache( $post['ID'] );
		$this->log( "Slug $oldslug renamed into $newslug." );
	}

	function action_sync_alt( $post, $old_filepath, $new_filepath ) {
		update_post_meta( $post['ID'], '_wp_attachment_image_alt', $post['post_title'] );
		$this->log( "Alt. Text set to {$post['post_title']}." );
	}

	function getoption( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[$option] ) ) {
				if ( $options[$option] == "off" ) {
						return false;
				}
				if ( $options[$option] == "on" ) {
						return true;
				}
				return $options[$option];
		}
		return $default;
	}

	function setoption( $option, $section, $value ) {
		$options = get_option( $section );
		if ( empty( $options ) ) {
				$options = array();
		}
		$options[$option] = $value;
		update_option( $section, $options );
	}
}
new RenameMediaFiles();
