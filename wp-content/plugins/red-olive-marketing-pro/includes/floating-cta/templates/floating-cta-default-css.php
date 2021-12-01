<style>
	/** 
	 * DESKTOP STYLES
	 **/
	@media screen and (min-width: 1024px) {
		.ro-floating-cta {
			z-index: 1000;
			position: fixed;
			<?php echo( $this->cta_meta['cta_display_side'] ); ?>: 0px;
			padding: <?php echo( $this->cta_meta['cta_padding'] ); ?>;
			top: <?php echo( $this->cta_meta['cta_distance_from_top'] ); ?>;
			background: rgba( <?php echo( $this->cta_meta['cta_background_color'] ); ?> );
		}

        /* Border */
		<?php if( $this->cta_meta['show_cta_border'] ): ?>
            .ro-floating-cta {
                border: <?php echo( $this->cta_meta['cta_border_width'] ); ?> solid <?php echo( $this->cta_meta['cta_border_color'] ); ?>;
                border-<?php echo( $this->cta_meta['cta_display_side'] ) ?>: 0px solid transparent;
            }
            .ro-floating-cta-prompt {
                display: none;
            }
		<?php endif; ?>

        /* Close Button */
        <?php if( $this->cta_meta['cta_line_1_type'] === 'shortcode' || $this->cta_meta['cta_line_2_type'] === 'shortcode' ): ?>
            <?php $side = ($this->cta_meta['cta_display_side'] === 'left') ? 'right' : 'left'; ?>
            .ro-fcta-close {
                float: right;
                font-size: 26px;
                cursor: pointer;
                line-height: 0px;
                padding: 8px 5px;
                margin-top: -10px;
                margin-right: -10px;
                border-radius: 50%;
                padding-bottom: 14px;
                display: inline-block;
                background-color: rgba(255,255,255,0.5);
            }
            .ro-fcta-close:before {
                content: "x";
            }
        <?php else: ?>
            .ro-fcta-close {
                display: none;
            }
        <?php endif; ?>

        /* Line 1 */
        .ro-fcta-line-1 {
            padding-top: 5px;
		}

        <?php if( $this->cta_meta['cta_line_1_type'] === 'text_link' ): ?>
            /* Line 1 */
            .ro-fcta-line-1 {
                color: <?php echo( $this->cta_meta['cta_line_1_text_color'] ); ?>;
            }

            .ro-fcta-line-1 a,
            .ro-fcta-line-1 a:link
            .ro-fcta-line-1 a:visited, 
            .ro-fcta-line-1 a:hover, 
            .ro-fcta-line-1 a:focus, 
            .ro-fcta-line-1 a:active {
                color: <?php echo( $this->cta_meta['cta_line_1_link_color'] ); ?>;
            }
        <?php endif; ?>

        <?php if( $this->cta_meta['cta_line_2_type'] === 'text_link' ): ?>
            /* Line 2 */
            .ro-fcta-line-2 {
                color: <?php echo( $this->cta_meta['cta_line_2_text_color'] ); ?>;
            }

            .ro-fcta-line-2 a, 
            .ro-fcta-line-2 a:link, 
            .ro-fcta-line-2 a:visited,
            .ro-fcta-line-2 a:hover,
            .ro-fcta-line-2 a:focus,
            .ro-fcta-line-2 a:active {
                color: <?php echo( $this->cta_meta['cta_line_2_link_color'] ); ?>;
            }
        <?php endif; ?>
	}

	/** 
	 * MOBILE STYLES
	 **/
    <?php if( $this->cta_meta['cta_line_1_type'] === 'shortcode' || $this->cta_meta['cta_line_2_type'] === 'shortcode' ): ?>
        <?php $prompt_state = 'block'; ?>
        <?php $cta_state = 'none'; ?>
    <?php else: ?>
        <?php $prompt_state = 'none'; ?>
        <?php $cta_state = 'block'; ?>
    <?php endif; ?>
	@media screen and (max-width: 1023px) {
	 	.ro-floating-cta, .ro-floating-cta-prompt {
			bottom: 0px;
			width: 100%;
			z-index: 1000;
			position: fixed;
            display: block;
	 		justify-content: center;
			padding: <?php echo( $this->cta_meta['cta_padding'] ); ?>;
			background: rgba( <?php echo( $this->cta_meta['cta_background_color'] ); ?> );
		}

        .ro-floating-cta {
            display: <?php echo $cta_state; ?>;
        }

        .ro-floating-cta-prompt {
            display: <?php echo $prompt_state; ?>;
            color: <?php echo( $this->cta_meta['cta_line_1_text_color'] ); ?>;
            text-align: center; /* Keep this. It's used to identify when prompt should be shown by jQuery */
        }

        /* Close Button */
        <?php if( $this->cta_meta['cta_line_1_type'] === 'shortcode' || $this->cta_meta['cta_line_2_type'] === 'shortcode' ): ?>
            .ro-fcta-close {
                float: right;
                font-size: 26px;
                cursor: pointer;
                line-height: 0px;
                padding: 8px 5px;
                margin-top: -10px;
                margin-right: -10px;
                border-radius: 50%;
                padding-bottom: 14px;
                display: inline-block;
                background-color: rgba(255,255,255,0.5);
            }
            .ro-fcta-close:before {
                content: "x";
            }
        <?php else: ?>
            .ro-fcta-close {
                display: none;
            }
        <?php endif; ?>

        /* Border */
		<?php if( $this->cta_meta['show_cta_border'] ): ?>
		.ro-floating-cta {
			border-top: <?php echo( $this->cta_meta['cta_border_width'] ); ?> solid <?php echo( $this->cta_meta['cta_border_color'] ); ?>;
		}
		<?php endif; ?>

        /* Line 1 */
		.ro-fcta-line-1 {
            padding-top: 15px;
			padding-right: <?php echo( $this->cta_meta['cta_padding'] ); ?>;
		}

        <?php if( $this->cta_meta['cta_line_1_type'] === 'text_link' ): ?>
            /* Line 1 */
            .ro-fcta-line-1 {
                color: <?php echo( $this->cta_meta['cta_line_1_text_color'] ); ?>;
                <?php if( $this->cta_meta['cta_line_1_type'] === 'text_link' ): ?>
                    text-align: center;
                <?php endif; ?>
            }

            .ro-fcta-line-1 a,
            .ro-fcta-line-1 a:link
            .ro-fcta-line-1 a:visited, 
            .ro-fcta-line-1 a:hover, 
            .ro-fcta-line-1 a:focus, 
            .ro-fcta-line-1 a:active {
                color: <?php echo( $this->cta_meta['cta_line_1_link_color'] ); ?>;
            }
        <?php endif; ?>

        <?php if( $this->cta_meta['cta_line_2_type'] === 'text_link' ): ?>
            /* Line 2 */
            .ro-fcta-line-2 {
                color: <?php echo( $this->cta_meta['cta_line_2_text_color'] ); ?>;
                <?php if( $this->cta_meta['cta_line_2_type'] === 'text_link' ): ?>
                    text-align: center;
                <?php endif; ?>
            }

            .ro-fcta-line-2 a, 
            .ro-fcta-line-2 a:link, 
            .ro-fcta-line-2 a:visited,
            .ro-fcta-line-2 a:hover,
            .ro-fcta-line-2 a:focus,
            .ro-fcta-line-2 a:active {
                color: <?php echo( $this->cta_meta['cta_line_2_link_color'] ); ?>;
            }
        <?php endif; ?>
	}
</style>
