/**
 * Brick System
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage jQuery Bricksystem Library
 */

// Init
var bricksystem, brick;
var debug = true;

/**
 * Function library for the bricksystem
 */
( function( $ ) {
	bricksystem = {
		/**
		 * Init the bricksystem
		 */
		init : function() {
			/**
			 * One of the main features in the bricksystem.
			 * This bundge of code is for the sort- and dropable
			 * stuff. Mostly it is inspired by the widgets.
			 */
			$( '.brick-box-sortables' ).sortable( {
				placeholder: 'sortable-placeholder',
				connectWith: '.meta-box-sortables',
				items: '.postbox',
				handle: '.hndle',
				cursor: 'move',
				distance: 2,
				tolerance: 'pointer',
				forcePlaceholderSize: true,
				helper: 'clone',
				opacity: 0.65,
				start: function( e, ui ) {
					$( 'body' ).css( {
						WebkitUserSelect: 'none',
						KhtmlUserSelect: 'none'
					} );
				},
				stop: function( e, ui ) {
					bricksystem.save_brick_order();
					$( 'body' ).css( {
						WebkitUserSelect: '',
						KhtmlUserSelect: ''
					} );
				},
				receive: function( e, ui ) {
					// Prepare the new brick
					var new_item, new_brick_id, the_brick_id, set_id, brick_type;
					set_id = bricksystem.get_url_parameter( 'brickset' );
					the_brick_id = bricksystem.next_free_brick_id();
					new_brick_id = 'brick-' + the_brick_id;
					
					// Build the new Brick
					new_item = $( '#normal-sortables' ).find( '.widget' );
					$( new_item ).attr( 'id', new_brick_id );
					brick_type = $( '#' + new_brick_id ).find( '#brick_type' ).attr( 'value' );
					brick_name = $( '#' + new_brick_id ).find( '.brick_title' ).html();
					
					// Add my brick
					bricksystem.add_brick( new_brick_id, set_id, brick_name, brick_type );
					
					// Kill the widget! Long live the postbox!
					$( '#' + new_brick_id ).removeClass( 'widget bricklet ui-draggable' ).addClass( 'postbox main_brick_handler opened' );
					$( '#' + new_brick_id ).find( '.widget-top' ).remove();
					$( '#' + new_brick_id ).find( '.widget-inside' ).remove();
					var inside = $( '#' + new_brick_id ).find( '.postbox' ).html();
					$( '#' + new_brick_id ).find( '.postbox' ).remove();
					$( '#' + new_brick_id ).html( inside );
					
					// Change all the IDs
					$( '#' + new_brick_id ).find( '.preview' ).attr( 'id', 'draftlink-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick-status' ).attr( 'id', 'status-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_edit_form' ).attr( 'id', 'form-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_slug' ).attr( 'id', 'brick_slug_' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_name' ).attr( 'id', 'brick_name_' + the_brick_id );
					$( '#' + new_brick_id ).find( '.publishbutton' ).attr( 'id', 'publish-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_name' ).attr( 'name', 'brick_name_' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_slug' ).attr( 'name', 'brick_slug_' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick_title' ).attr( 'id', 'brick-title-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.brick-options' ).attr( 'id', 'inside-brick-' + the_brick_id );
					$( '#' + new_brick_id ).find( '.preview' ).attr( 'href', "javascript:bricksystem.save_brick(" + the_brick_id + ", 'draft');" );
					$( '#' + new_brick_id ).find( '.widget-action' ).attr( 'href', "javascript:bricksystem.slider('" + new_brick_id + "','inside');" );
					$( '#' + new_brick_id ).find( '.publishbutton' ).attr( 'onClick', "bricksystem.save_brick(" + the_brick_id + ", 'publish'); return false;" );
					$( '#' + new_brick_id ).find( '#save_brick_options' ).attr( 'onClick', 'bricksystem.save_brick_options(' + the_brick_id + '); return false;' );
					$( '#' + new_brick_id ).find( '.submitdelete' ).attr( 'href', "javascript:bricksystem.move_brick_to_trash(" + the_brick_id + ", " + set_id + ");" );
					
					// Change the ids for some bricks - god damn it
					$( '#' + new_brick_id ).find( '.editor_text' ).attr( 'id', 'content_' + the_brick_id );
					$( '#' + new_brick_id ).find( '.picture_chooser' ).attr( 'id', 'picture_chooser_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#picture_chooser_link' ).attr( 'href', "javascript:brick.picture_chooser(" + the_brick_id + ");" );
					
					// Simple Text
					$( '#' + new_brick_id ).find( '#ed_em' ).attr( 'class', 'ed_button ed_i_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_ul' ).attr( 'class', 'ed_button ed_ul_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_ol' ).attr( 'class', 'ed_button ed_ol_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_li' ).attr( 'class', 'ed_button ed_li_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_link' ).attr( 'class', 'ed_button ed_a_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_img' ).attr( 'class', 'ed_button ed_img_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_strong' ).attr( 'class', 'ed_button ed_b_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_code' ).attr( 'class', 'ed_button ed_code_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_del' ).attr( 'class', 'ed_button ed_strike_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_underline' ).attr( 'class', 'ed_button ed_u_' + the_brick_id );
					$( '#' + new_brick_id ).find( '#ed_block' ).attr( 'class', 'ed_button ed_bquote_' + the_brick_id );
					
					// Even more of that! Don't I get it easier?
					$( '#' + new_brick_id ).find( '#ed_em' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'i\' );' );
					$( '#' + new_brick_id ).find( '#ed_ul' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'ul\' );' );
					$( '#' + new_brick_id ).find( '#ed_ol' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'ol\' );' );
					$( '#' + new_brick_id ).find( '#ed_li' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'li\' );' );
					$( '#' + new_brick_id ).find( '#ed_link' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'a\' );' );
					$( '#' + new_brick_id ).find( '#ed_img' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'img\' );' );
					$( '#' + new_brick_id ).find( '#ed_strong' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'b\' );' );
					$( '#' + new_brick_id ).find( '#ed_code' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'code\' );' );
					$( '#' + new_brick_id ).find( '#ed_del' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'strike\' );' );
					$( '#' + new_brick_id ).find( '#ed_underline' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'u\' );' );
					$( '#' + new_brick_id ).find( '#ed_block' ).attr( 'onclick', 'brick.insert_tag( ' + the_brick_id + ', \'bquote\' );' );
				}
			} );
			/**
			 * Define the dragable elements
			 */
			$( '.bricklet' ).draggable( {
				opacity: 0.7,
				helper: 'clone',
				connectToSortable: '.brick-box-sortables'
			} );
		},
		
		/**
		 * This function returns the next free brick id
		 */
		next_free_brick_id : function () {
			var post_vars = {
				action: 'next_free_brick_id'
			};
			var brick_id = $.ajax( {
				url: ajaxurl,
				data: post_vars,
				async: false,
				success: function ( response ) {
					return response;
				}
			} ).responseText;
			return brick_id;
		},
		
		/**
		 * Check the order of the bricks and save it.
		 */
		save_brick_order : function () {
			var post_vars, page_columns = $( '.columns-prefs input:checked' ).val() || 0;
			post_vars = {
				action: 'save_brick_order',
			};
			$( '.brick-box-sortables' ).each( function() {
				post_vars['order[' + this.id.split( '-' )[0] + ']'] = $( this ).sortable( 'toArray' ).join( ',' );
			} );
			$.post( ajaxurl, post_vars, function( response ) {
				if ( true == debug ) {
					$( '#ajax-response' ).html( response );
				}
			} );
		},
		
		/**
         * Save if a brick box is opened or not
         */
		save_state : function() {
			var closed = $( '.postbox' ).filter( '.closed' ).map( function() { return this.id; } ).get().join( ',' );
			var opened = $( '.postbox' ).filter( '.opened' ).map( function() { return this.id; } ).get().join( ',' );
			var data = {
				action: 'save_brick_state',
				closed: closed,
				opened: opened
			};

			$.post( ajaxurl, data, function( response ) {
				if ( true == debug ) {
					$( '#ajax-response' ).html( response );
				}
			} );
		},
		
		/**
		 * Delets the brickset
		 * @param {int} brickset The ID of the brickset
		 */
		delete_brickset : function ( brickset ) {
			if ( confirm( bs_strings.brickset_delete ) ) { 
				var data = {
					action: 'delete_brickset',
					brickset: brickset
				};
		
				$.post( ajaxurl, data, function( response ) {
					$( '#tag-' + brickset ).fadeOut();
					$( '#ajax-response' ).html( response );
				} );
			}
		},
		
		/**
		 * Adds a brickset
		 */
		add_brickset : function () {
			if ( '' == $( '#set-name' ).val() ) {
				$( '#set-name' ).addClass( 'error' );
			}
			else {
				var data = {
					action: 'add_brickset',
					set_name: $( '#set-name' ).val(),
					set_slug: $( '#set-slug' ).val(),
					set_desc: $( '#set-desc' ).val()
				};

				$.post( ajaxurl, data, function( response ) {
					// Empty values
					$( '#set-name' ).val('');
					$( '#set-slug' ).val('');
					$( '#set-desc' ).val('');
					$( '#set-name' ).removeClass( 'error' );
					// Prepend Table Contents
					$( '#the-list' ).prepend( response );
				} );
			}
		},
		
		/**
		 * Edits the brickset
		 */
		edit_brickset : function () {
			var data = {
				action: 'edit_brickset',
				set_id: $( '#set-id' ).val(),
				set_name: $( '#set-name' ).val(),
				set_slug: $( '#set-slug' ).val(),
				set_desc: $( '#set-desc' ).val(),
			};

			$.post( ajaxurl, data, function( response ) {
				$( '#ajax-response' ).html( response );
				$( '#updated' ).delay( 1000 ).fadeOut();
			} );
		},
		
		/**
		 * Add a brick. This function is called in bricksystem.init
		 * @param {int} brick_id
		 * @param {int} set_id
		 * @param {string} brick_name
		 * @param {string} brick_slug
		 */
		add_brick : function ( brick_id, set_id, brick_name, brick_slug ) {
			var data = {
				action: 'add_brick_to_set',
				brick_id: brick_id,
				set_id: set_id,
				brick_name: brick_name,
				brick_slug: brick_slug
			};

			$.post( ajaxurl, data, function( response ) {
				if ( true == debug ) {
					$( '#ajax-response' ).html( response );
				}
			} );
		},
		
		/**
		 * Save the options like name and slug
		 * @param {int} brick_id
		 */
		save_brick_options : function ( brick_id ) {
			if ( '' == $( '#brick_name_' + brick_id ).val() ) {
				$( '#brick_name_' + brick_id ).addClass( 'error' );
			}
			else {
				var data = {
					action: 'save_brick_options',
					brick_slug: $( '#brick_slug_' + brick_id).val(),
					brick_name: $( '#brick_name_' + brick_id).val(),
					brick_id: brick_id
				};

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				$.post( ajaxurl, data, function( response ) {
					$( '#brick_name_' + brick_id ).removeClass( 'error' );
					$( '#brick-title-' + brick_id ).html( $( '#brick_name_' + brick_id ).val() );
					$( '#inside-brick-' + brick_id ).animate( { 'backgroundColor' : '#CCEEBB' }, 50 ).animate( { 'backgroundColor' : '#F6F6F6' }, 400 );
				} );
			}
		},
		
		/**
		 * Save the content of the brick
		 * @param {int} brick_id
		 * @param {string} method
		 */
		save_brick : function ( brick_id, method ) {
			
	        // find class
			if ( undefined != $( '#form-' + brick_id ).find( '.editor_text' ).val() ) {
				var ed = tinyMCE.get( 'content' + brick_id );
                $( '#form-' + brick_id ).find( '.editor_text' ).val( ed.getContent() );
			}
			
			var data = {
				action: 'save_brick',
				brick_id: brick_id,
				method: method,
				params: $( '#form-' + brick_id ).serialize()
			};
			
			$.post( ajaxurl, data, function( response ) {
				if ( 'draft' == method ) {
					$( '#draftlink-' + brick_id ).html( bs_strings.save );
					$( '#publish-' + brick_id ).val( bs_strings.publish );
					$( '#status-' + brick_id ).html( bs_strings.brick_drafted );
				}
				else if ( 'publish' == method ) {
					$( '#draftlink-' + brick_id ).html( bs_strings.save_as_draft );
					$( '#publish-' + brick_id ).val( bs_strings.update );
					$( '#status-' + brick_id ).html( bs_strings.brick_published );
				}
				if ( true == debug ) {
					$( '#ajax-response' ).html( response );
				}
				$( '#inside-brick-' + brick_id ).animate( { 'backgroundColor' : '#CCEEBB' }, 50 ).animate( { 'backgroundColor' : '#F6F6F6' }, 400 );
			} );
		},
		
		/**
		 * Moves the brick to the trash
		 * @param {int} brick_id
		 * @param {int} set_id
		 */
		move_brick_to_trash : function ( brick_id, set_id ) {
			if ( confirm( bs_strings.brick_to_trash ) ) {
				var holder = 'notfound';
				if ( '' != $.find( '#trash-brick-holder' ) ) {
					holder = 'found';
				}
				var data = {
					action: 'move_brick_to_trash',
					holder: holder,
					brick_id: brick_id,
					set_id: set_id
				};
				
				$.post( ajaxurl, data, function( response ) {
					if ( '' != $.find( '#trash-brick-holder' ) ) {
						$( '#trash-brick-holder' ).fadeIn();
						$( '#trash-right' ).append( response );
					}
					else {
						$( '#widgets-right' ).append( response );
					}
					$( '#brick-' + brick_id ).fadeOut();
				} );
			}
		},
		
		/**
		 * Restores the brick out of the trash
		 * @param {int} brick_id
		 */
		restore_brick : function ( brick_id ) {
			var data = {
				action: 'restore_brick',
				brick_id: brick_id
			}
			$.post( ajaxurl, data, function( response ) {
				$( '#normal-sortables' ).append( response ).fadeIn('fast');
				$( '#trash-' + brick_id ).fadeOut();
			} );
		},
		
		/**
		 * Delete a brick
		 * @param {int} brick_id
		 */
		delete_brick : function ( brick_id ) {
			var data = {
				action: 'delete_brick',
				brick_id: brick_id
			};
			
			$.post( ajaxurl, data, function( response ) {
				if ( true == debug ) {
					$( '#ajax-response' ).html( response );
				}
				$( '#trash-' + brick_id ).fadeOut();
				$( '#normal-sortables' ).children( '#brick-' + brick_id ).remove();
			} );
		},
		
		/**
		 * Empty the trash
		 * @param {int} set_id
		 */
		empty_brickset_trash : function ( set_id ) {
			if ( confirm( bs_strings.brickset_emtpy_trash ) ) { 
				var data = {
					action: 'empty_brickset_trash',
					set_id: set_id
				}
				
				$.post( ajaxurl, data, function ( response ) {
					var affected_bricks = response.split( ',' );
					$.each( affected_bricks, function ( key, value ) {
						if ( '' != value ) {
							$( '#normal-sortables' ).children( '#brick-' + value ).remove();
						}
					} );
					
					$( '.trashed' ).fadeOut();
					$( '#trash-brick-holder' ).fadeOut();
				} );
			}
		},
		
		/**
		 * This opens the brickbox
		 * @param {mixed} parent
		 * @param {mixed} child
		 */
		slider : function ( parent, child ) {
			var element = $( '#' + parent ).children( '.' + child ).css( 'display' );
			if ( 'none' == element ) {
				$( '#' + parent ).children( '.' + child ).slideDown( 'fast' );
				$( '#' + parent ).removeClass( 'closed' );
				$( '#' + parent ).addClass( 'opened' );
			} else {
				$( '#' + parent ).children( '.' + child ).slideUp( 'fast' );
				$( '#' + parent ).removeClass( 'opened' );
				$( '#' + parent ).addClass( 'closed' );
			}
			bricksystem.save_state();
		},
		
		/**
		 * Get the url parameters. Analog to $_GET
		 * @param {mixed} param_name
		 */
		get_url_parameter : function ( param_name ) {
			var search_string = window.location.search.substring( 1 ), i, val, params = search_string.split( '&' );
			for ( i = 0; i < params.length; i++) {
		    val = params[i].split( '=' );
			    if (val[0] == param_name) {
			    	return unescape( val[1] );
			    }
			}
			return null;
		},
	};
	$( document ).ready( function( $ ) { bricksystem.init(); } );
} )( jQuery );