<script>
roPopUpDisplayed = false;
	jQuery(function($){
		$('#ro-pop-up-trigger').fancybox(
			{
				closeBtn: true,
				padding: [0,20,10,20],
				helpers : {
					overlay : {
						closeClick: false,
						css : {
							'background' : 'rgba( <?php echo( $this->pop_up_meta['page_overlay_color'] ); ?> )'
						}
					}
				},
				afterClose: function(){
					body = {
						action: 'ro_dismiss_popup',
						pop_up_id: <?php echo $this->pop_up->ID; ?>
					};
					$.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', body, function( result ) {}
						).always( function(){
							$.fancybox.close();
						});
				}
			}
		);

        
		<?php if( $this->pop_up_meta['pop_up_trigger'] === 'delay' ): ?>
			/** 
			 * Set FancyBox to be triggered if delay option is set
			 */
            <?php if( $this->pop_up_meta['pop_up_delay_type'] === 'session' ): ?>
				var timerCheckInterval = setInterval( function(){
					if( roPopUpDisplayed ) return;
					$.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', { action: 'ro_check_session_timer' }, function( result ){
						if( result.success ){
							$("#ro-pop-up-trigger").trigger('click');
							roPopUpDisplayed = true;
							clearInterval( timerCheckInterval );
						}
					}, 'json' );
				}, 3000);
            <?php else: ?>
				setTimeout( function() {
					if( roPopUpDisplayed ) return;
					$("#ro-pop-up-trigger").trigger('click');
					roPopUpDisplayed = true;
				}, <?php echo $this->pop_up_meta['pop_up_delay'] ?>000);
			<?php endif; ?>

       
		<?php elseif( $this->pop_up_meta['pop_up_trigger'] === 'scroll' ): ?>
			 /** 
			 * Set FancyBox to be triggered if scroll distance option is set
			 */
			<?php if( $this->pop_up_meta['scroll_distance_type'] === 'pixels' ): ?>
				$( window ).scroll( function() {
					if( roPopUpDisplayed ) return;
					if( $( document ).scrollTop() >= roPopUpScrollDistance ){
						$("#ro-pop-up-trigger").trigger('click');
						roPopUpDisplayed = true;
					}
				});
			<?php else: ?>
				$( window ).scroll( function() {
					if( roPopUpDisplayed ) return;

					var winheight = $( window ).height();
				    var docheight = $( document ).height();
				    var scrollTop = $( window ).scrollTop();
				    var trackLength = docheight - winheight;
				    var pctScrolled = Math.floor(scrollTop/trackLength * 100);

					if( pctScrolled >= roPopUpScrollPercentage ){
						$("#ro-pop-up-trigger").trigger('click');
						roPopUpDisplayed = true;
					}
				});
			<?php endif; ?>

       
		<?php elseif( $this->pop_up_meta['pop_up_trigger'] === 'exit' ): ?>

			 /**
			 * Set FancyBox to be triggered if exit intent option is set
			 */
        function triggerPopup() {
            var date = localStorage.getItem("popup-done");
            var now = new Date();
            if (date === null || date < now.getTime()) {
                $("#ro-pop-up-trigger").trigger('click');
                localStorage.setItem('popup-done', now.getTime() + (60 * 60 * 8 * 1000));
            } else {
                console.log('no none for you');
            }
        }

			glio.init( 
				[ 'top', function(){
					triggerPopup();
				} ],
				[ 'top-left', function(){
                    triggerPopup();
				} ],
				[ 'top-right', function(){
                    triggerPopup();
				} ],
			);

			// Detect mobile devices' exit intent
			if ( /Mobi/.test( navigator.userAgent ) ) {
				var mobileScroll;
				$( window ).scroll( function() {

					// Check if user scrolls up a bit
					if( mobileScroll && ( ( $( document ).scrollTop() + 20 ) < mobileScroll ) ){
                        triggerPopup();
					}else if( mobileScroll == undefined || $( document ).scrollTop() > mobileScroll ){
						mobileScroll = $( document ).scrollTop();
					}
				});
			}
		<?php endif; ?>
	
        /** 
         * Link type pop up submitted
         */
		$('.apply-button a').click(function(e){
			e.preventDefault();
            href = $(this).attr('href');

            acceptPopUp( href );
		});

        /** 
         * Email type pop up submitted
         */
		$('.ro-add-email a').click(function(e){
			e.preventDefault();

            acceptPopUp( false );
            sendMailChimpRequest();
        });
        
        /** 
         * Comment type pop up submitted
         */
        $('.ro-add-comment a').click(function(e){
            e.preventDefault();

            acceptPopUp( false );

            if( $('.js-pop-up-comment').val() ){
                processPopUpComment();
            }

            if( $('.js-pop-up-email-address').val() && $('.js-pop-up-email-accept').is(':checked') ){
                sendMailChimpRequest();
            }else{
                popUpThankYouResponse();
            }
        });

        /** 
         * Accept pop up so it doesn't display again
         */
        function acceptPopUp( href ){
            body = {
                action: 'ro_accept_popup',
                pop_up_id: <?php echo $this->pop_up->ID; ?>
            };

			$.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', body, function( result ) {
				if( result.success && href ) {
                    location.href = href;
                    $.fancybox.close();
				}else if( ! result.success ){
                    console.log( 'Accept popup failure', result ); //@DEBUG
                    $.fancybox.close();
                }
			}, 'json');
        }

        /** 
         * Set up and send the MailChimp request
         */
        function sendMailChimpRequest(){
            var data = {
                action: 'ro_add_email_to_mailchimp',
                email: $('.js-pop-up-email-address').val(),
                api_key: '<?php echo $this->pop_up_meta['mc_info']->apiKey; ?>',
                list_id: '<?php echo $this->pop_up_meta['mc_info']->list; ?>'
            };

            $.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', data, function( result ) {
                if( result.success ) {
                    popUpThankYouResponse();
                }else{
                    console.log( 'Email send failure: ', result ); //@DEBUG
                    $.fancybox.close();
                }
            }, 'json');
        }

        /** 
         * Display pop up thank you page or message
         */
        function popUpThankYouResponse(){
            <?php if( $this->pop_up_meta['thank_you_response'] === 'page' ): ?>
                location.href = '<?php echo $this->pop_up_meta['thank_you_page']; ?>'
                $.fancybox.close();
            <?php elseif( $this->pop_up_meta['thank_you_response'] === 'message' ): ?>
                $('#ro-pop-up').html([
                    '<div class="large-text"><?php echo $this->pop_up_meta['message_large_text']; ?></div>',
                    '<div class="medium-text"><?php echo $this->pop_up_meta['message_medium_text']; ?></div>',
                    '<div class="ignore">Dismiss</div>'
                ].join(''));
            <?php endif; ?>
        }

        /** 
         * Process a pop up comment
         */
        function processPopUpComment(){
            var data = {
                action: 'ro_process_comment',
                comment: $('.js-pop-up-comment').val(),
                email: $('.js-pop-up-email-address').val(),
                notification_email: '<?php echo $this->pop_up_meta['notification_email']; ?>'
            };

            $.post( '<?php echo admin_url( 'admin-ajax.php' ) ?>', data, function( result ) {
                if( ! result.success ) {
                    console.log( 'Comment process failure: ', result ); //@DEBUG
                }
            }, 'json');
        }
        
        /** 
         * Ignore Pop Up
         */
		$('#ro-pop-up').on('click', '.ignore', function(){
            body = {
                action: 'ro_dismiss_popup',
                pop_up_id: <?php echo $this->pop_up->ID; ?>
            };

			$.post( 
                '<?php echo admin_url( 'admin-ajax.php' ) ?>', body, function( result ) {}
            ).always( function(){
				$.fancybox.close();
            });
		});

		  /** 
         * Overlay Click Pop Up
         */
		$('#ro-pop-up').on('click', '.fancybox-close', function(){
            body = {
                action: 'ro_dismiss_popup',
                pop_up_id: <?php echo $this->pop_up->ID; ?>
            };

			$.post( 
                '<?php echo admin_url( 'admin-ajax.php' ) ?>', body, function( result ) {}
            ).always( function(){
				$.fancybox.close();
            });
		});


		<?php
		if( $_SESSION['ro_popup_end_time_' . $this->pop_up->ID] ) : ?>
			function formatMinutesSeconds( diff ) {
				var hours = Math.floor( diff / 1000 / 60 / 60 );
				if( hours == 0 ) hours = '00';
				if( hours.toString().length == 1 ) hours = '0' + hours;

				var minutes = Math.floor( diff / 1000 / 60 ) % 60;
				if( minutes == 0 ) minutes = '00';
				if( minutes.toString().length == 1 ) minutes = '0' + minutes;

				var seconds = Math.floor( diff / 1000 ) % 60;
				if( seconds.toString().length == 1 ) seconds = '0' + seconds;

				var formattedTime = hours + ':' + minutes + ':' + seconds;
				if( formattedTime == '-1:-1:-1' ) {
					$.fancybox.close();
					return false;
				}
				return formattedTime;
			}

			function getMinutesAndSeconds( time ) {
				var currentDate = new Date();
				var diff = time - currentDate;
				return formatMinutesSeconds( diff );
			}

			function calculateTime( time ) {
				var diff = getMinutesAndSeconds( pageLoadTime );
				if( diff ) {
					$('.time-remaining span').html( diff );
					setTimeout( calculateTime, 1000);
				}
			}

			// get the time when the page was loaded.
			var pageLoadTime = new Date();
			pageLoadTime.setSeconds(
                pageLoadTime.getSeconds() + <?php echo $_SESSION['ro_popup_end_time_' . $this->pop_up->ID] - strtotime('now') ?>
            );

			setTimeout( calculateTime, 800);
		<?php endif ?>
	});
</script>
