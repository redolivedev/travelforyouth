var RoFloatingCTA = {};

(function($){

	RoFloatingCTA = {
		init: function(){
            RoFloatingCTA.insertFloatingCTA();
            RoFloatingCTA.addEventListeners();
		},

		insertFloatingCTA: function(){
			var line1 = RoFloatingCTA.getLineContent('1');
			var line2 = RoFloatingCTA.getLineContent('2');

			var cta = [
                '<div class="ro-floating-cta" >',
                    '<div class="ro-fcta-close"></div>',
					'<div class="ro-fcta-line-1" >',
						line1,
					'</div>',
					'<div class="ro-fcta-line-2" >',
						line2,
					'</div>',
                '</div>',
                '<div class="ro-floating-cta-prompt">',
                    cta_meta['cta-mobile-prompt-text'],
                '</div>'
			].join('');

			$('body').append(cta);
        },
        
        // Gets line content based on line type
        getLineContent: function( lineNumber ){
            if( cta_meta['cta_line_' + lineNumber + '_type'] === 'shortcode' ){
                return cta_meta['cta_line_' + lineNumber + '_shortcode'];
            }

            if( cta_meta['cta_line_' + lineNumber + '_type'] === 'text_link' ){

                // If there is a link URL, put the line in an anchor tag
                if( cta_meta['cta_line_' + lineNumber + '_link'] && cta_meta['cta_line_' + lineNumber + '_link'].length ){
                    return [
                        '<a href="' + cta_meta['cta_line_' + lineNumber + '_link'] + '" >',
                            cta_meta['cta_line_' + lineNumber + '_text'],
                        '</a>'
                    ].join('');
                }else{
                    return cta_meta['cta_line_' + lineNumber + '_text'];
                }
            }

            return '';
        },

        addEventListeners: function(){
            $('body').on('click', '.ro-fcta-close', RoFloatingCTA.closeFloatingCTA);
            $('body').on('click', '.ro-floating-cta-prompt', RoFloatingCTA.openMobileCTA);
        },

        closeFloatingCTA: function(){
            $('.ro-floating-cta').hide();
            if( $('.ro-floating-cta-prompt').css('text-align') == 'center' ){
                $('.ro-floating-cta-prompt').show();
            }
        },
        
        openMobileCTA: function(){
            $('.ro-floating-cta').show();
            $('.ro-floating-cta-prompt').hide();
        }
	};

	$(document).ready(RoFloatingCTA.init());
}(jQuery));
