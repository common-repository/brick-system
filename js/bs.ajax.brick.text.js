/**
 * Brick System - jQuery Brick Library
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Simple Text
 */

/**
 * A helper to insert some strings into a textarea
 */
jQuery.fn.extend( {
    insert_at_caret: function( my_value, obj_button, tag_type, tag_list_open, tag_list_close ){
	    return this.each( function ( i ) {
		    if ( document.selection ) {
		        this.focus();
		        sel = document.selection.createRange();
		        sel.text = my_value;
		        this.focus();
		    }
		    else if ( this.selectionStart || '0' == this.selectionStart ) {
				if ( this.selectionStart != this.selectionEnd ) {
					obj_button.val( tag_type );
				}
		        var start_pos = this.selectionStart;
		        var end_pos = this.selectionEnd;
		        var scroll_top = this.scrollTop;
				if ( this.selectionStart != this.selectionEnd ) {
			        this.value = this.value.substring( 0, start_pos ) + tag_list_open[ tag_type ] + this.value.substring( end_pos, start_pos ) + tag_list_close[ tag_type ] + this.value.substring( end_pos, this.value.lenght );
				}
				else {
					this.value = this.value.substring( 0, start_pos ) + my_value + this.value.substring( end_pos, this.value.lenght );
				}
		        this.focus();
		        this.selectionStart = end_pos + my_value.length;
		        this.selectionEnd = end_pos + my_value.length;
		        this.scrollTop = scroll_top;
		    }
			else {
		        this.value += my_value;
		        this.focus();
		    }
	    } )
    }
} );

/**
 * Function library for the bricks
 */
( function( $ ) {
	bs_st = {
		init : function() {
		},

        /**
         * This function inserts some HTML-Tags into a textarea
         * @param {int} brick_id
         * @param {mixed} tag_type
         */
		insert_tag : function ( brick_id, tag_type ) {
			var current_button_value = $( '.ed_' + tag_type + '_' + brick_id ).val();
            var default_value = 'http://';
			if ( 'a' == tag_type && '/' != current_button_value[0] ) {
                var url = prompt( bs_strings.enter_url, default_value );
            }
			if ( 'img' == tag_type ) {
                var img_url = prompt( bs_strings.enter_img_url, default_value );
            }
			var tag_list_open = {
                b: '<strong>',
                i: '<em>',
                u: '<u>',
                strike: '<strike>',
                bquote: '\n<blockquote>',
                code: '<code>',
                ul: '\n<ul>\n',
                ol: '\n<ol>\n',
                li: '\t<li>',
                a: '<a href="' + url + '">',
                img: '<img src="' + img_url + '" />'
            };
			var tag_list_close = {
                b: '</strong>',
                i: '</em>',
                u: '</u>',
                strike: '</strike>',
                bquote: '</blockquote>\n',
                code: '</code>',
                ul: '</ul>\n',
                ol: '</ol>\n',
                li: '</li>\n',
                a: '</a>',
                img: ''
            };
			if ( '/' != current_button_value[0] ) {
				var tag_list = tag_list_open;
				if ( 'img' != current_button_value ) {
					$( '.ed_' + tag_type + '_' + brick_id ).val( '/' + tag_type );
				}
			}
			else {
				var tag_list = tag_list_close;
				$( '.ed_' + tag_type + '_' + brick_id ).val( tag_type );
			}
			// Bind the function to the textarea
			$( '#content_' + brick_id ).insert_at_caret( tag_list[ tag_type ], $( '.ed_' + tag_type + '_' + brick_id ), tag_type, tag_list_open, tag_list_close );
		}
	};
	$( document ).ready( function( $ ) { bs_st.init(); } );
} )( jQuery );