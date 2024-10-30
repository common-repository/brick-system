/**
 * Brick System - jQuery Brick Library
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Picture Chooser
 */

/**
 * Function library for the brick simple picture
 */
( function( $ ) {
	bs_sp = {
		init : function() {
		},
		
		/**
		 * The picture chooser
		 * @param {int} brick_id
		 */
		picture_chooser : function( brick_id ) {
			var element = $( '#picture_chooser_' + brick_id ).css( 'display' );
			if ( 'none' == element ) {
				var data = {
					brick_id: brick_id,
					action: 'brick_load_pictures'
				};
				var to_prepend = $.ajax( {
					url: ajaxurl,
					data: data,
					async: false,
					success: function ( response ) {
						return response;
					}
				} ).responseText;
				
				$( '#picture_chooser_' + brick_id ).prepend( to_prepend ).slideDown();
				$( '#form-' + brick_id ).children( '.picture_chooser_container' ).children( '.picture_chooser_link' ).removeClass( 'closed' ).addClass( 'opened' );
			}
			else {
				$( '#picture_chooser_' + brick_id ).slideUp();
				$( '#picture_chooser_' + brick_id ).children( '#media-item' ).fadeOut();
				$( '#form-' + brick_id ).children( '.picture_chooser_container' ).children( '.picture_chooser_link' ).removeClass( 'opened' ).addClass( 'closed' );
			}
		},
		
		/**
		 * Closes the picture chooser and sends the url of the picture to the brick
		 * @param {int} brick_id
		 * @param {string} picture_path
		 */
		take_picture : function ( brick_id, picture_path ) {
			$( '#form-' + brick_id ).find( '.urlfield' ).val( picture_path );
			$( '#picture_chooser_' + brick_id ).slideUp();
			$( '#picture_chooser_' + brick_id ).children( '#media-item' ).fadeOut();
			$( '#form-' + brick_id ).children( '.picture_chooser_container' ).children( '.picture_chooser_link' ).removeClass( 'opened' ).addClass( 'closed' );
		}
	};
	$( document ).ready( function( $ ) { bs_sp.init(); } );
} )( jQuery );