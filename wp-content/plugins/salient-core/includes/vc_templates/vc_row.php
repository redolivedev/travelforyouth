<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

   extract(shortcode_atts(array(
	  "type" => 'in_container',
	  'bg_image'=> '',
    'bg_image_animation' => 'none',
	  'background_image_mobile_hidden' => '',
		'background_image_loading' => '',
	  'bg_position'=> '',
		'bg_position_x' => '50%',
		'bg_position_y' => '50%',
	  'bg_repeat' => '',
	  'parallax_bg' => '',
	  'parallax_bg_speed' => 'fast',
	  'bg_color'=> '',
	  'exclude_row_header_color_inherit' => '',
	  'text_align'=> '',
	  'vertically_center_columns' => '',

	  'mouse_based_parallax_bg' => '',
	  'layer_one_image' => '',
	  'layer_two_image' => '',
	  'layer_three_image' => '',
	  'layer_four_image' => '',
	  'layer_five_image' => '',

	  'layer_one_strength' => '.20',
	  'layer_two_strength' => '.40',
	  'layer_three_strength' => '.60',
	  'layer_four_strength' => '.80',
	  'layer_five_strength' => '1.00',
	  'scene_position' => '',
	  'mouse_sensitivity' => '10',

	  'video_bg'=> '',
	  'enable_video_color_overlay'=> '',
	  'video_overlay_color'=> '',
	  'video_external'=> '',
	  'video_webm'=> '',
	  'video_mp4'=> '',
	  'video_ogv'=> '',
	  'video_image'=> '',
	  'video_mute' => '',

	  "top_padding" => "0",
		"top_padding_tablet" => "",
		"top_padding_phone" => "",
	  "bottom_padding" => "0",
		"bottom_padding_phone" => "",
		"bottom_padding_tablet" => "",

		'translate_x' => '',
		'translate_x_tablet' => '',
		'translate_x_phone' => '',
    'translate_y' => '',
		'translate_y_tablet' => '',
		'translate_y_phone' => '',

	  'text_color' => 'dark',
	  'custom_text_color' => '',
	  'id' => '',
	  'class' => '',
	  'full_height' => '',
	  'columns_placement' => 'middle',
    'column_margin' => 'default',

		'column_direction' => 'default',
		'column_direction_tablet' => 'default',
		'column_direction_phone' => 'default',

	  'enable_gradient' => 'false',
	  'color_overlay' => '',
	  'color_overlay_2' => '',
	  'gradient_direction' => 'left_to_right',
	  'overlay_strength' => '0.3',
	  'equal_height' => '',
	  'content_placement' => '',
	  'row_name' => '',
	  'full_screen_row_position' => 'middle',
	  'disable_ken_burns' => '',
	  'disable_element' => '',

		'row_border_radius' => 'none',
		'row_border_radius_applies' => 'bg',
	  'enable_shape_divider' => '',
	  'shape_type' => '',
	  'shape_divider_color' => '',
    'shape_divider_bring_to_front' => '',
	  'shape_divider_position' => '',
	  'shape_divider_height' => '50',
		'shape_divider_height_tablet' => '',
		'shape_divider_height_phone' => '',
    'zindex' => ''

	  ),
	$atts));

  global $post;

	// CSS perspective.
	$css_perspective_class = '';

  // Top level row class.
  $top_level_class = '';

  if( in_the_loop() ) {

    if( !isset($GLOBALS['nectar_vc_row_count']) ) {
      $GLOBALS['nectar_vc_row_count'] = 0;
    }
    $GLOBALS['nectar_vc_row_count']++;

    if( !is_single() && $GLOBALS['nectar_vc_row_count'] == 1 && isset($post->ID) ) {

      $nectar_page_header_bool = nectar_header_section_check($post->ID);
      if( $nectar_page_header_bool == false ) {

        $top_level_class .= 'top-level ';

        if ( isset( $content ) && strpos( $content, '[nectar_slider' ) !== false && strpos( $content, 'full_width="true"' ) !== false ) {
          $top_level_class .= 'full-width-ns ';
        }

      }

    }
		
		if( isset( $content ) && isset($post->ID) ) {
			
			// Global Section
			if( strpos( $content, '[nectar_global_section' ) !== false ) {
				$top_level_class .= 'has-global-section ';
			}
			
			// CSS perspective
			if( strpos( $content, '"flip-in-vertical"' ) !== false ||
			    strpos( $content, '"slight-twist"' ) !== false ) {

				 // Prevent if using incompatible el.
				 if( strpos( $content, 'sticky="true"' ) === false &&
				     strpos( $content, '"vertical_scrolling"' ) === false ) {
					  	$css_perspective_class = ' flip-in-vertical-wrap';
				 }

			} // element exists that needs perspective.
		} // content is set.

  }


	wp_enqueue_style( 'js_composer_front' );
	wp_enqueue_script( 'wpb_composer_front_js' );
	wp_enqueue_style( 'js_composer_custom_css' );

	if( $mouse_based_parallax_bg === 'true' ) {
		wp_enqueue_script('nectar-parallax');
	}

  $style = null;
  $bg_props = null;
	$etxra_class = null;
	$using_image_class = null;
	$using_bg_color_class = null;
	$using_custom_text_color = null;
	$lazy_image_attr = '';

  $nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
  $nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

	// Force full width BG if using shape divider and in container.
	if( $enable_shape_divider === 'true' && $type === 'in_container' ) {
		$type = 'full_width_background';
	}

	$disable_class = '';
	if ( 'yes' !== $disable_element ) {

		if( $this->shortcode == 'vc_row_inner' ) {
      $text_color = null;
    }

		if( !empty($bg_image) ) {

			if(!preg_match('/^\d+$/',$bg_image)) {

				if( 'lazy-load' === $background_image_loading || 
				     property_exists('NectarLazyImages', 'global_option_active') && true === NectarLazyImages::$global_option_active && 'skip-lazy-load' !== $background_image_loading ) {
					$lazy_image_attr .= ' data-nectar-img-src="'.esc_url($bg_image).'"';
				}	else {
					$bg_props .= 'background-image: url('. esc_url($bg_image) . '); ';
				}

			}
			else {
				$bg_image_src = wp_get_attachment_image_src($bg_image, 'full');

				if( isset($bg_image_src[0]) ) {

					if( 'lazy-load' === $background_image_loading || 
					    property_exists('NectarLazyImages', 'global_option_active') && true === NectarLazyImages::$global_option_active && 'skip-lazy-load' !== $background_image_loading ) {
						$lazy_image_attr .= ' data-nectar-img-src="'.esc_url($bg_image_src[0]).'"';
					}	else {
						$bg_props .= 'background-image: url('. esc_url($bg_image_src[0]) . '); ';
					}

				}

			}


			// Custom bg pos.
			if( 'custom' === $bg_position ) {
				$bg_props .= 'background-position: '. esc_attr(intval($bg_position_x)) .'% '. esc_attr(intval($bg_position_y)) .'%; ';
			} else {
				$bg_props .= 'background-position: '. esc_attr($bg_position) .'; ';
			}


			// Pattern bgs.
			if(strtolower($bg_repeat) === 'repeat'){
				$bg_props .= 'background-repeat: '. esc_attr(strtolower($bg_repeat)) .'; ';
				$etxra_class = ' no-cover';
			} else {
				$bg_props .= 'background-repeat: '. esc_attr(strtolower($bg_repeat)) .'; ';
				$etxra_class = null;
			}

			$using_image_class = ' using-image';
		}

		if( !empty($bg_color) ) {

			$bg_props .= 'background-color: '. esc_attr($bg_color).'; ';

			if( $exclude_row_header_color_inherit !== 'true' ) {
        $using_bg_color_class = ' using-bg-color';
      } else {
        $using_bg_color_class = ' using-bg-color-excluded';
      }

		}

		$page_full_screen_rows = (isset($post->ID)) ? get_post_meta($post->ID, '_nectar_full_screen_rows', true) : '';

    // Disable unneeded BG animations.
    if( $video_bg && !empty($video_webm) || $video_bg && !empty($video_mp4) || $video_bg && !empty($video_external) ) {
      if( strtolower($parallax_bg) === 'true' ) {
        $parallax_bg = '';
      }
      if( $bg_image_animation === 'zoom-out-reveal' ) {
        $bg_image_animation = 'none';
      }
    }


		if( strtolower($parallax_bg) === 'true' && $page_full_screen_rows !== 'on' ){
			$parallax_class = 'parallax_section';
			$parallax_speed = 'data-parallax-speed="'.esc_attr($parallax_bg_speed).'"';
		} else {
			$parallax_class = '';
			$parallax_speed = null;
		}

		$vertically_center_class = null;

		if( $type === 'full_width_content' && $equal_height !== 'yes' ) {
			// v12.5+ uses CSS for all
			$equal_height = 'yes';

			if( strtolower($vertically_center_columns) === 'true' ) {
				$content_placement = 'middle';
			} else {
				$content_placement = 'top';
			}

		}
		else if( strtolower($vertically_center_columns) === 'true' && $equal_height !== 'yes' ) {
      // v11+ uses CSS for vertical center option instead of JS.
      $equal_height = 'yes';
      $content_placement = 'middle';
		}



		$row_percent_padding_attr = '';

		if( $page_full_screen_rows !== 'on' ) {

			if( strpos($top_padding,'%') !== false ) {

        $leading_zero = (intval($top_padding) < 10) ? '0' : '';
        $row_percent_padding_attr .= 'data-top-percent="'. esc_attr($top_padding) .'" ';
				$style .= 'padding-top: calc(100vw * 0.'. $leading_zero . intval($top_padding) .'); ';
			} else {
				$style .= 'padding-top: '. intval($top_padding) .'px; ';
			}

			if( strpos($bottom_padding,'%') !== false ) {

        $leading_zero = (intval($bottom_padding) < 10) ? '0' : '';
        $row_percent_padding_attr .= 'data-bottom-percent="'.esc_attr($bottom_padding).'" ';
				$style .= 'padding-bottom: calc(100vw * 0.'. $leading_zero . intval($bottom_padding) .'); ';

			} else {
				$style .= 'padding-bottom: '. intval($bottom_padding) .'px; ';
			}

      // Transforms.
      if( !empty($translate_y) || !empty($translate_x) ) {

          for( $i = 0; $i < 2; $i++ ) {

            if( $i == 0 ) {
              $style .= '-webkit-transform: ';
            } else {
              $style .= ' transform: ';
            }

            if( !empty($translate_y) ) {
                if( strpos($translate_y,'%' ) !== false){
                    $style .= ' translateY('. intval($translate_y) .'%)';
                }
								else if( strpos($translate_y,'vh' ) !== false ) {
									$style .= ' translateY('. intval($translate_y) .'vh)';
								}
								else {
                    $style .= ' translateY('. intval($translate_y) .'px)';
                }
            }

            if( !empty($translate_x) ) {
                if( strpos($translate_x,'%' ) !== false){
                    $style .= ' translateX('. intval($translate_x) .'%)';
                }
								else if( strpos($translate_x,'vh' ) !== false ) {
									$style .= ' translateX('. intval($translate_x) .'vh)';
								}
								else {
                    $style .= ' translateX('. intval($translate_x) .'px)';
                }
            }
            $style .= ';';

          } //loop

      }


      // z-index.
      if( !empty($zindex) ) {
         $style .= ' z-index: '.esc_attr($zindex).';';
      }

		}

		$midnight_color = $text_color;

		if( $text_color === 'custom' && !empty($custom_text_color) ) {
			$midnight_color = 'dark';
			$style .= 'color: '. esc_attr($custom_text_color) .'; ';
			$using_custom_text_color = 'data-using-ctc="true"';
		}

		// Row type class.
		if( $type === 'in_container' ) {
			$main_class = "";
		}
    else if( $type === 'full_width_background' ){
			$main_class = "full-width-section ";
		}
    else if( $type === 'full_width_content' ){
			$main_class = "full-width-content ";
		}

		// Remove in container possibility when using fullpage.js.
		if( $page_full_screen_rows === 'on' && $type === 'in_container') {
      $main_class = "full-width-section ";
    }

		// Remove ken burns when using fullpage.js.
		$disable_ken_burns_class = null;

		if( $page_full_screen_rows === 'on' && !empty($disable_ken_burns) && $disable_ken_burns === 'yes' ) {
			$disable_ken_burns_class = ' disable_ken_burns';
		}

		// Equal height column option.
		if( $equal_height === 'yes' || $nectar_using_VC_front_end_editor && strtolower($vertically_center_columns) === 'true' ) {
			$equal_height_class = ' vc_row-o-equal-height vc_row-flex ';
    }
		else {
		 	$equal_height_class = '';
    }

		if ( ! empty( $content_placement ) ) {
			$equal_height_class .= ' vc_row-o-content-' . $content_placement.' ';
		}

		// Row full height option.
		if ( ! empty( $full_height ) && $page_full_screen_rows != 'on' ) {
			$main_class .= 'vc_row-o-full-height ';
			if ( ! empty( $columns_placement ) ) {

				$equal_height_class = ' vc_row-o-equal-height vc_row-flex ';

				if ( ! empty( $content_placement ) ) {
					$equal_height_class .= ' vc_row-o-content-' . $content_placement.' ';
				}

				$main_class .= 'vc_row-o-columns-' . $columns_placement;

			}
		}


		$row_id = (!empty($id) && $page_full_screen_rows !== 'on') ? $id: uniqid("fws_");

		$fullscreen_anchor_id = null;
		if( $page_full_screen_rows === 'on' && !empty($id) ) {
			$fullscreen_anchor_id = 'data-fullscreen-anchor-id="'.$id.'"';
		}

		$midnight_attr = 'data-midnight="'.esc_attr(strtolower($midnight_color)).'"';

		// Border radius
		$border_radius_attrs = '';
		if( !empty($row_border_radius) && 'none' != $row_border_radius ) {
			$border_radius_attrs = ' data-br="'.esc_attr($row_border_radius).'" data-br-applies="'.esc_attr($row_border_radius_applies).'"';
		}


		// Dynamic style classes.
		if( function_exists('nectar_el_dynamic_classnames') ) {
			$dynamic_el_styles = nectar_el_dynamic_classnames('row', $atts);
		} else {
			$dynamic_el_styles = '';
		}

    $bg_mobile_hidden = ( !empty($background_image_mobile_hidden) ) ? ' data-bg-mobile-hidden="'.esc_attr($background_image_mobile_hidden).'"' : '';

    // Begin row output.
	   echo'
		<div id="'. esc_attr($row_id) .'" '.$fullscreen_anchor_id. $border_radius_attrs .' data-column-margin="'.esc_attr($column_margin).'" '.$midnight_attr.' '.$row_percent_padding_attr. $bg_mobile_hidden. ' class="wpb_row vc_row-fluid vc_row '. $top_level_class . $main_class . $disable_class . $equal_height_class . $parallax_class . $vertically_center_class . ' '. $class . $dynamic_el_styles.'" '.$using_custom_text_color.' style="'.$style.'">';

		if( $page_full_screen_rows === 'on' ) {
      echo '<div class="full-page-inner-wrap-outer"><div class="full-page-inner-wrap" data-name="'.esc_attr($row_name).'" data-content-pos="'.esc_attr($full_screen_row_position).'"><div class="full-page-inner">';
    }

		// Row bg.
    $using_bg_overlay = ( !empty($color_overlay) || !empty($color_overlay_2) ) ? 'true' : 'false';
		echo '<div class="row-bg-wrap" data-bg-animation="'.esc_attr($bg_image_animation).'" data-bg-overlay="'.esc_attr($using_bg_overlay).'"><div class="inner-wrap' . $using_image_class . '">';
    echo '<div class="row-bg' . $using_image_class . $using_bg_color_class . $disable_ken_burns_class . $etxra_class.'" '.$parallax_speed.' style="'.$bg_props.'"'.$lazy_image_attr.'></div>';
    echo '</div>';

    // Row color overlay.
    $row_overlay_style = null;

    if( !empty($color_overlay) || !empty($color_overlay_2) ) {

      $row_overlay_style = 'style="';
      $gradient_direction_deg = '90deg';

      if(empty($color_overlay)) {
        $color_overlay = 'transparent';
      }
      if(empty($color_overlay_2)) {
        $color_overlay_2 = 'transparent';
      }

      // Legacy option conversion.
      if( $overlay_strength === 'image_trans' ) {
  			$overlay_strength = '1';
      }

      switch($gradient_direction) {
        case 'left_to_right' :
          $gradient_direction_deg = '90deg';
          break;
        case 'left_t_to_right_b' :
          $gradient_direction_deg = '135deg';
          break;
        case 'left_b_to_right_t' :
          $gradient_direction_deg = '45deg';
          break;
        case 'top_to_bottom' :
          $gradient_direction_deg = 'to bottom';
          break;
      }

      if( $enable_gradient === 'true' ) {

    			if($color_overlay !== 'transparent' && $color_overlay_2 === 'transparent') {
            $color_overlay_2 = 'rgba(255,255,255,0.001)';
          }
    			if($color_overlay === 'transparent' && $color_overlay_2 !== 'transparent') {
            $color_overlay = 'rgba(255,255,255,0.001)';
          }

    			if( $gradient_direction === 'top_to_bottom' ) {

    				if($color_overlay_2 === 'transparent' || $color_overlay_2 === 'rgba(255,255,255,0.001)') {
    					$row_overlay_style .= 'background: linear-gradient('. $gradient_direction_deg .',' . $color_overlay . ' 0%,' . $color_overlay_2 . ' 75%);  opacity: '. esc_attr($overlay_strength). '; ';
    				}

    				else if($color_overlay === 'transparent' || $color_overlay === 'rgba(255,255,255,0.001)') {
    					$row_overlay_style .= 'background: linear-gradient('. $gradient_direction_deg .',' . $color_overlay . ' 25%,' . $color_overlay_2 . ' 100%);  opacity: '. esc_attr($overlay_strength) .'; ';
    				}

    				else if( $color_overlay !== 'transparent' && $color_overlay_2 !== 'transparent') {
    				  $row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('. $gradient_direction_deg . ',' . $color_overlay . ' 0%,' . $color_overlay_2 . ' 100%);  opacity: '. esc_attr($overlay_strength) .'; ';
    				}

    			}
    			else if( $gradient_direction === 'left_to_right' ) {

    				if( $color_overlay === 'transparent' || $color_overlay === 'rgba(255,255,255,0.001)' ) {
    					$row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('.$gradient_direction_deg .',' . $color_overlay . ' 25%,' . $color_overlay_2 . ' 100%);  opacity: '. esc_attr($overlay_strength) .'; ';
    				}

            if( $color_overlay_2 === 'transparent' || $color_overlay_2 === 'rgba(255,255,255,0.001)' ) {
              if( $overlay_strength === '1' ) {
                $row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('.$gradient_direction_deg .',' . $color_overlay . ' 25%,' . $color_overlay_2 . ' 100%);  opacity: '. esc_attr($overlay_strength) .'; ';
              } else {
                $row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('.$gradient_direction_deg .',' . $color_overlay . ' 10%,' . $color_overlay_2 . ' 75%);  opacity: '. esc_attr($overlay_strength) .'; ';
              }

    				}

    				if( $color_overlay !== 'transparent' && $color_overlay_2 !== 'transparent') {
    					$row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('.$gradient_direction_deg.',' . $color_overlay . ' 0%,' . $color_overlay_2 . ' 100%);  opacity: '.esc_attr($overlay_strength).'; ';
    				}
    			}

					else if( $gradient_direction === 'radial' ) {
						$row_overlay_style .= 'background: '. $color_overlay .'; background: radial-gradient(50% 50% at 50% 50%,' . $color_overlay . ' 0%,' . $color_overlay_2 . ' 100%);  opacity: '.esc_attr($overlay_strength).'; ';
					}

    			else {
    				$row_overlay_style .= 'background: '. $color_overlay .'; background: linear-gradient('.$gradient_direction_deg.',' . $color_overlay . ' 0%,' . $color_overlay_2 . ' 100%);  opacity: '.esc_attr($overlay_strength).'; ';
    			}


  		}

      // No gradient.
      else {

    			if( !empty($color_overlay) ) {
    				$row_overlay_style .= 'background-color:' . $color_overlay . ';  opacity: '.esc_attr($overlay_strength).'; ';
    			}

  		}

      $row_overlay_style .= '"';

    }

		if( !empty($row_overlay_style) ) {
	    echo '<div class="row-bg-overlay" '. $row_overlay_style .'></div>';
		}
    echo '</div>';

		// Mouse based parallax layer.
		if( $mouse_based_parallax_bg === 'true' ) {

	        echo '<ul class="nectar-parallax-scene" data-scene-position="'.esc_attr($scene_position).'" data-scene-strength="'.esc_attr($mouse_sensitivity).'">';
	        echo '<li class="layer" data-depth="0.00"></li>';

	        if( !empty($layer_one_image) ) {

	        	if( !preg_match('/^\d+$/',$layer_one_image) ) {
	        		$layer_one_image_src = $layer_one_image;
	        	} else {
	        		$layer_one_image_src = wp_get_attachment_image_src($layer_one_image, 'full');
	        		$layer_one_image_src = $layer_one_image_src[0];
	        	}

	        	echo '<li class="layer" data-depth="'.esc_attr($layer_one_strength).'"><div style="background-image:url(\''. esc_url($layer_one_image_src) .'\');"></div></li>';
	        }

	        if( !empty($layer_two_image) ) {

	        	if( !preg_match('/^\d+$/',$layer_two_image) ) {
	        		$layer_two_image_src = $layer_two_image;
	        	} else {
	        		$layer_two_image_src = wp_get_attachment_image_src($layer_two_image, 'full');
	        		$layer_two_image_src = $layer_two_image_src[0];
	        	}

	        	echo '<li class="layer" data-depth="'.esc_attr($layer_two_strength).'"><div style="background-image:url(\''. esc_url($layer_two_image_src) .'\');"></div></li>';
	        }

	        if( !empty($layer_three_image) ) {

	        	if( !preg_match('/^\d+$/',$layer_three_image) ) {
	        		$layer_three_image_src = $layer_three_image;
	        	} else {
	        		$layer_three_image_src = wp_get_attachment_image_src($layer_three_image, 'full');
	        		$layer_three_image_src = $layer_three_image_src[0];
	        	}

	        	echo '<li class="layer" data-depth="'.esc_attr($layer_three_strength).'"><div style="background-image:url(\''. esc_url($layer_three_image_src) .'\');"></div></li>';
	        }

	        if( !empty($layer_four_image) ) {

	        	if( !preg_match('/^\d+$/',$layer_four_image) ) {
	        		$layer_four_image_src = $layer_four_image;
	        	} else {
	        		$layer_four_image_src = wp_get_attachment_image_src($layer_four_image, 'full');
	        		$layer_four_image_src = $layer_four_image_src[0];
	        	}

	        	echo '<li class="layer" data-depth="'.esc_attr($layer_four_strength).'"><div style="background-image:url(\''. esc_url($layer_four_image_src) .'\');"></div></li>';
	        }
	        if( !empty($layer_five_image) ) {

	        	if(!preg_match('/^\d+$/',$layer_five_image)){
	        		$layer_five_image_src = $layer_five_image;
	        	} else {
	        		$layer_five_image_src = wp_get_attachment_image_src($layer_five_image, 'full');
	        		$layer_five_image_src = $layer_five_image_src[0];
	        	}

	        	echo '<li class="layer" data-depth="'.esc_attr($layer_five_strength).'"><div style="background-image:url(\''. esc_url($layer_five_image_src) .'\');"></div></li>';
	        }
	        echo '</ul>';

	        global $nectar_options;
	        $loading_animation    = (!empty($nectar_options['loading-image-animation']) && !empty($nectar_options['loading-image'])) ? $nectar_options['loading-image-animation'] : null;
    			$default_loader       = (empty($nectar_options['loading-image']) && !empty($nectar_options['theme-skin']) && $nectar_options['theme-skin'] === 'ascend') ? '<span class="default-loading-icon spin"></span>' : null;
    			$default_loader_class = (empty($nectar_options['loading-image']) && !empty($nectar_options['theme-skin']) && $nectar_options['theme-skin'] === 'ascend') ? 'default-loader' : null;


		}

		// Video bg layer.
		if( $video_bg ) {

			// Parse video image.
			if( strpos($video_image, "http") !== false ){
				$video_image_src = $video_image;
			} else if( preg_match('/^\d+$/', $video_image) ) {
				$video_image_src = wp_get_attachment_image_src($video_image, 'full');
				$video_image_src = $video_image_src[0];
			}

			if( $enable_video_color_overlay !== 'true' ) {
        $video_overlay_color = null;
      }
      ?>

			<div class="video-color-overlay" data-color="<?php echo esc_attr( $video_overlay_color ); ?>"></div>
      <?php if( isset($video_image_src) && !empty($video_image_src) ) : ?>
			     <div class="mobile-video-image" style="background-image: url(<?php echo esc_url( $video_image_src ); ?>)"></div>
      <?php endif; ?>

			<div class="nectar-video-wrap" data-bg-alignment="<?php echo esc_attr( $bg_position ); ?>">
				<div class="nectar-video-inner">
        <?php
				if( !empty($video_external) && vc_extract_youtube_id( $video_external ) ) {
					wp_enqueue_script( 'vc_youtube_iframe_api_js' );
					echo '<div class="nectar-youtube-bg"><span>'.$video_external.'</span></div>';
				} else {
					echo '<video class="nectar-video-bg" width="1800" height="700" preload="auto" loop autoplay muted playsinline>';
				    if(!empty($video_webm)) { echo '<source src="'. esc_url( $video_webm ) .'" type="video/webm">'; }
				    if(!empty($video_mp4)) { echo '<source src="'. esc_url( $video_mp4 ) .'"  type="video/mp4">'; }
				    if(!empty($video_ogv)) { echo '<source src="'. esc_url( $video_ogv ) .'" type="video/ogg">'; }
				   echo '</video>';
				}
        ?>
    		</div>
			 </div>

			<?php


		}


		$extra_container_div         = false;
		$extra_container_div_closing = false;

		if( $page_full_screen_rows === 'on' && $main_class === "full-width-section ") {

			$extra_container_div = true;
			$extra_container_div_closing = true;

			$pattern = get_shortcode_regex();

			if ( preg_match_all( '/'. $pattern .'/s', $content, $matches )  && array_key_exists( 0, $matches ))  {

				if($matches[0][0]){
					if( strpos($matches[0][0],'nectar_slider') !== false && strpos($matches[0][0],'full_width="true"') !== false
						|| strpos($matches[0][0],' type="full_width_content"') !== false && strpos($matches[0][0],'nectar_slider') !== false && strpos($matches[0][0],'[vc_column width="1/1"') !== false ) {
						$extra_container_div = false;
						$extra_container_div_closing = false;
					}
				}
			}
		}


    // Shape divider layer.
		if( $enable_shape_divider === 'true' ) {

      $shape_divider_length = ($shape_divider_position === 'both') ? 2 : 1;
      $shape_divider_pos    = ($shape_divider_position === 'both') ? array('top','bottom') : array($shape_divider_position);

      for( $i = 0; $i < $shape_divider_length; $i++ ) {

   			$shape_divider_height_val = (!empty($shape_divider_height) ) ? 'style=" height:'.intval($shape_divider_height) . 'px;"' : 'style=" height: 50px;"';

        // Percent height.
        $using_percent_shape_divider_attr = '';
        if( strpos($shape_divider_height,'%') !== false ) {
          $using_percent_shape_divider_attr = 'data-using-percent-val="true"';
          $shape_divider_height_val = 'style=" height:'.intval($shape_divider_height) . '%;"';
        }

        $no_bg_color_class = (empty($shape_divider_color)) ? 'no-color ': '';

   			echo '<div class="nectar-shape-divider-wrap '.$no_bg_color_class.'" '. $shape_divider_height_val .' '.$using_percent_shape_divider_attr.' data-height="'.esc_attr($shape_divider_height).'" data-front="'. esc_attr( $shape_divider_bring_to_front ).'" data-style="'. esc_attr( $shape_type ).'" data-position="'. esc_attr( $shape_divider_pos[$i] ) .'" >';

  			switch( $shape_type ) {
  				case 'curve' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 0 c 0 0 200 50 500 50 s 500 -50 500 -50 v 101 h -1000 v -100 z"></path> </svg>';
  					break;
  				case 'curve_asym' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 20 0 50 0 100 100 Z"></path> </svg>';
  					break;
          case 'curve_asym_2' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 50 0 80 0 100 100 Z"></path> </svg>';
  					break;
  				case 'tilt' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 10 0 0 0 10"></polygon> </svg>';
  					break;
          case 'tilt_alt' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="100 10 100 0 -4 10"></polygon> </svg>';
  					break;
  				case 'triangle' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <polygon points="501 53.27 0.5 0.56 0.5 100 1000.5 100 1000.5 0.66 501 53.27"/></svg>';
  					break;
          case 'fan' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1003.92 91" preserveAspectRatio="none"> <polygon class="cls-1" points="502.46 46.31 1 85.67 1 91.89 1002.91 91.89 1002.91 85.78 502.46 46.31"/><polygon class="cls-2" points="502.46 45.8 1 0 1 91.38 1002.91 91.38 1002.91 0.1 502.46 45.8"/><rect class="cls-3" y="45.81" width="1003.92" height="46.09"/>
            </svg>';
  					break;
          case 'waves' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none"> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
  					break;
          case 'speech' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 45.86 h 458 c 29 0 42 19.27 42 19.27 s 13 -19.27 42.74 -19.27 h 457.26 v 54.14 h -1000 z"></path>  </svg>';
  					break;
          case 'straight_section' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 10, 104 0, 0 0, 0 10"></polygon> </svg>';
  					break;
          case 'clouds' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 983.71 4.47 a 56.19 56.19 0 0 0 -37.61 14.38 a 15.24 15.24 0 0 0 -25.55 -0.55 a 40.65 40.65 0 0 0 -55.45 13 a 15.63 15.63 0 0 0 -22.69 1.52 a 73.82 73.82 0 0 0 -98.57 27.91 a 14.72 14.72 0 0 0 -9.31 0.55 a 26.13 26.13 0 0 0 -42.63 1.92 a 39.08 39.08 0 0 0 -47 10.08 a 18.45 18.45 0 0 0 -34.18 -0.45 a 12.21 12.21 0 0 0 -14.23 0.9 a 11.47 11.47 0 0 0 -16.59 -6 a 47.2 47.2 0 0 0 -66.12 -4.07 a 21.32 21.32 0 0 0 -26.48 -4.91 a 15 15 0 0 0 -29 -7.79 a 10.47 10.47 0 0 0 -14 5.13 a 31.55 31.55 0 0 0 -50.68 12.32 a 23 23 0 0 0 -28.69 -5.34 a 54.54 54.54 0 0 0 -89.93 5.71 a 16.3 16.3 0 0 0 -22.71 2.3 a 33.41 33.41 0 0 0 -44.93 9.65 a 17.72 17.72 0 0 0 -9.79 -2.94 h -0.22 a 29 29 0 0 0 -39.66 -12.26 a 75.24 75.24 0 0 0 -94 -12.19 a 22.91 22.91 0 0 0 -14.78 -5.34 h -0.69 a 33 33 0 1 0 -52.53 31.55 h -29.69 v 143.45 h 79.5 v -57.21 a 75.26 75.26 0 0 0 132.93 -46.7 a 28.88 28.88 0 0 0 12.78 -6.86 a 17.61 17.61 0 0 0 12.79 0 a 33.41 33.41 0 0 0 63.93 -7.44 a 54.56 54.56 0 0 0 101.57 18.56 v 7.65 h 140.21 a 47.23 47.23 0 0 0 79.55 -15.88 l 51.25 1.95 a 39.07 39.07 0 0 0 67.12 2.55 l 29.76 1.13 a 73.8 73.8 0 0 0 143.76 -16.75 h 66.17 a 56.4 56.4 0 1 0 36.39 -99.53 z"></path>  </svg>';
  					break;
          case 'waves_opacity' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">  <path d="M 850.23 235.79 a 1.83 1.83 0 0 0 -0.8 -3.24 c -10.23 -2 -53.38 -23.41 -97.44 -43.55 c -244.99 -112 -337.79 97.38 -432.99 104 c -115 8 -217 -87 -330 -37 c 0 0 9 15 9 42 v -1 h 849 l 2 -55 s -2.87 -3 1.23 -6.21 z"></path>  <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
  					break;
          case 'waves_opacity_alt' :
  					echo '<svg class="nectar-shape-divider" aria-hidden="true" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
            <path d="M 1000 299 l 2 -279 c -155 -36 -310 135 -415 164 c -102.64 28.35 -149 -32 -232 -31 c -80 1 -142 53 -229 80 c -65.54 20.34 -101 15 -126 11.61 v 54.39 z"></path> <path d="M 1000 286 l 2 -252 c -157 -43 -302 144 -405 178 c -101.11 33.38 -159 -47 -242 -46 c -80 1 -145.09 54.07 -229 87 c -65.21 25.59 -104.07 16.72 -126 10.61 v 22.39 z"></path> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path>
             </svg>';
  					break;
          case 'curve_opacity' :
  					echo '<svg class="nectar-shape-divider" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 14 s 88.64 3.48 300 36 c 260 40 514 27 703 -10 l 12 28 l 3 36 h -1018 z"></path> <path d="M 0 45 s 271 45.13 500 32 c 157 -9 330 -47 515 -63 v 86 h -1015 z"></path> <path d="M 0 58 s 188.29 32 508 32 c 290 0 494 -35 494 -35 v 45 h -1002 z"></path> </svg>';
  					break;
          case 'mountains' :
  					echo '<svg class="nectar-shape-divider" fill="'.esc_attr($shape_divider_color).'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
            <path d="M 1014 264 v 122 h -808 l -172 -86 s 310.42 -22.84 402 -79 c 106 -65 154 -61 268 -12 c 107 46 195.11 5.94 275 137 z"></path>   <path d="M -302 55 s 235.27 208.25 352 159 c 128 -54 233 -98 303 -73 c 92.68 33.1 181.28 115.19 235 108 c 104.9 -14 176.52 -173.06 267 -118 c 85.61 52.09 145 123 145 123 v 74 l -1306 10 z"></path>
            <path d="M -286 255 s 214 -103 338 -129 s 203 29 384 101 c 145.57 57.91 178.7 50.79 272 0 c 79 -43 301 -224 385 -63 c 53 101.63 -62 129 -62 129 l -107 84 l -1212 12 z"></path>
            <path d="M -24 69 s 299.68 301.66 413 245 c 8 -4 233 2 284 42 c 17.47 13.7 172 -132 217 -174 c 54.8 -51.15 128 -90 188 -39 c 76.12 64.7 118 99 118 99 l -12 132 l -1212 12 z"></path>
            <path d="M -12 201 s 70 83 194 57 s 160.29 -36.77 274 6 c 109 41 184.82 24.36 265 -15 c 55 -27 116.5 -57.69 214 4 c 49 31 95 26 95 26 l -6 151 l -1036 10 z"></path> </svg>';
  					break;

  			}

  			echo '</div>';

      } // top or bottom loop

		} // using shape divider.


    if( $extra_container_div === true ) {
      echo '<div class="container">';
    }
    echo '<div class="row_col_wrap_12 col span_12 '. esc_attr(strtolower($text_color)) .' '. esc_attr($text_align) . esc_attr($css_perspective_class).'">'. do_shortcode($content) .'</div></div>';
    if($extra_container_div_closing === true) {
      echo '</div>';
    }

		if( $page_full_screen_rows === 'on' ) {
      echo '</div></div></div><!--inner-wrap-->';
    }

	} // end disable row option.

?>
