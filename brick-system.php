<?php
/*
   Plugin Name: Wordpress Brick System
   Plugin URI: http://hughwillfayle.de/wordpress/brick-system
   Description: A Typo3-Like Asset Management System
   Version: 0.3
   Author: Thomas Herzog
   Author URI: http://hughwillfayle.de/
*/

/**
 * We have really much TODO here for future versions
 *  * Feature: Dynamic Lists Brick
 *  * Feature: Teaser Brick with serval templates ( infoboxes, teaser )
 *  * Feature: Widget with random brick from a brickset
 * 	* Feature: Clipboard for bricks ( coypable bricks )
 *  * Feature: Copyable Bricksets
 *  * Feature: Planable Publishing
 *  * Feature: Authorstuff
 *  * Feature: Revisions and Autosave
 *  * Feature: Drag and Drop for trashed bricks
 *  * Feature: Remember opened bricks by user
 *  * Feature: Statistics
 *  * Code: Security Checks ( nonces )
 *  * Code: Hook for wp-search
 *  * Code: Hook for plugin-deinstall
 *  * Code: Build Debug Modus
 *  * Misc: Tutorials ( en )
 *  * Misc: Help Textes ( en )
 */

/**
 * Brick System
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Controller
 */

require_once 'php/bricksystem.helpers.php';
require_once 'php/bricksystem.view.php';
require_once 'php/bricksystem.model.php';

class brick_system extends brick_system_model {
	
	/**
	 * Get the object of this class
	 */
	public function get_object () {
		new self;
	}

	// Charching ma lazor
	public function __construct ( $menu = true ) {
		// Imporatant vars
		$this->plugin_url  = get_bloginfo( 'url' ) . '/wp-admin/edit.php?post_type=page&page=brick_system';
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->brick_path  = $this->plugin_path . 'php/';
		// Load Bricks and get objekt list
		$this->bricks      = $this->load_bricks();
		
		// Frontend
		add_shortcode( 'brick', array( &$this, 'load_brick_shortcode' ) );
		add_shortcode( 'brickset', array( &$this, 'load_brickset_shortcode' ) );
		
		// Widgets
		if ( user_can( get_current_user_id(), 'edit_theme_options' ) && is_admin() ) {
			wp_enqueue_script( 'bricksystem_widgets', plugin_dir_url( __FILE__ ) . 'js/bs.ajax.widget.js' );
		}
		
		// Backend
		if ( user_can( get_current_user_id(), 'edit_pages' ) && is_admin() ) {
			// Load CSS if we're on the page
			if ( 'brick_system' == $_GET['page'] ) {
				wp_admin_css( 'media' );
				wp_admin_css( 'global' );
				wp_admin_css( 'widgets' );
				wp_admin_css( 'wp-admin' );
				wp_enqueue_style( 'brickset', plugin_dir_url( __FILE__ ) . 'css/style.css' );
			}
			
			// Needed vars
			$this->plugin_action = $_GET['action'];
			$this->brick_set_id  = $_GET['brickset'];
			
			// Localisation and Kick off
			load_plugin_textdomain( 'thbs', false, 'brick-system/i18n' );
			if ( $menu == true ) {
				add_action( 'admin_menu', array( &$this, 'init' ) );
			}

			// jQuery, JSON and AJAX Stuff Standards
			wp_localize_script( 'brickset', 'bs_strings', $this->localize_vars() );
			add_thickbox();
			wp_enqueue_script( 'json-form' );
			wp_enqueue_script( 'brickset', plugin_dir_url( __FILE__ ) . 'js/bs.ajax.brickset.js', array( 'jquery', 'json2', 'wp-lists', 'thickbox', 'wp-ajax-response', 'thickbox', 'postbox', 'suggest', 'post', 'utils', 'editor', 'word-count', 'media-upload', 'quicktags', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' ) );
			// Bricks
			wp_enqueue_script( 'brick_text', plugin_dir_url( __FILE__ ) . 'js/bs.ajax.brick.text.js' );
			wp_enqueue_script( 'brick_text', plugin_dir_url( __FILE__ ) . 'js/bs.ajax.brick.lists.js' );
			wp_enqueue_script( 'brick_picture', plugin_dir_url( __FILE__ ) . 'js/bs.ajax.brick.picture.js' );
			
			// Standard Ajax actions
			add_action( 'wp_ajax_save_brick',           array( $this, 'ajax_save_brick' ) );
			add_action( 'wp_ajax_add_brickset',         array( $this, 'ajax_add_brickset' ) );
			add_action( 'wp_ajax_delete_brick',         array( $this, 'ajax_delete_brick' ) );
			add_action( 'wp_ajax_restore_brick',        array( $this, 'ajax_restore_brick' ) );
			add_action( 'wp_ajax_edit_brickset',        array( $this, 'ajax_edit_brickset' ) );
			add_action( 'wp_ajax_delete_brickset',      array( $this, 'ajax_delete_brickset' ) );
			add_action( 'wp_ajax_save_brick_order',     array( $this, 'ajax_save_brick_order' ) );
			add_action( 'wp_ajax_add_brick_to_set',     array( $this, 'ajax_add_brick_to_set' ) );
			add_action( 'wp_ajax_save_brick_state',     array( $this, 'ajax_save_brick_state' ) );
			add_action( 'wp_ajax_next_free_brick_id',   array( $this, 'ajax_next_free_brick_id' ) );
			add_action( 'wp_ajax_move_brick_to_trash',  array( $this, 'ajax_move_brick_to_trash' ) );
			add_action( 'wp_ajax_brick_load_pictures',  array( $this, 'ajax_brick_load_pictures' ) );
			add_action( 'wp_ajax_save_brick_options',   array( $this, 'ajax_update_brick_options' ) );
			add_action( 'wp_ajax_empty_brickset_trash', array( $this, 'ajax_empty_brickset_trash' ) );
		}
	}
	
	/**
	 * Add Menu
	 */
	public function init () {
		// Menu
		add_pages_page( __( 'Bricksets', 'thbs' ), __( 'Bricksets', 'thbs' ), 'edit_pages', 'brick_system', array( &$this, 'backend' ) );
	}
	
	/**
	 * Backend Controller
	 */
	public function backend () {
		brick_system_view::wrapper_start();
		switch ( $this->plugin_action ) {
			case 'edit':
				$this->edit_brickset();
				break;
			default:
				$bricksets = $this->get_bricksets();
				brick_system_view::manage_bricksets( $bricksets, $this->plugin_url );
				break;
		}
		brick_system_view::wrapper_end();
	}
	
	/**
	 * This is the main brickset edit form
	 */
	public function edit_brickset () {
		// Left
		$bricks = $this->get_bricks( $_GET['brickset'] );
		brick_system_view::edit_brickset_left( $bricks );
		
		// Right
		$brickset = $this->get_brickset( $_GET['brickset'] );
		$trashed_bricks = $this->get_trashed_bricks( $_GET['brickset'] );
		brick_system_view::edit_brickset_right( $this->bricks, $trashed_bricks, $brickset );
	}
	
	/**
	 * God save our gracious brick,
	 * Long live our noble brick,
	 * God save the brick!
	 * Send it victorious,
	 * Happy and glorious,
	 * Long to display for us;
	 * God save the brick!
	 */
	public function ajax_save_brick () {
		// Prepare values
		$fields = explode( '&', $_POST['params'] );
		foreach( $fields as $field ){
			$field_key_value = explode( '=', $field );
			$key = urldecode( $field_key_value[0] );
			$value = urldecode( $field_key_value[1] );
			$values[$key] = $value;
		}
		
		// Prepare brick and load brick specific save method
		$brick = $this->get_brick( $_POST['brick_id'] );
		$brick_save_method = $this->bricks[$brick->post_mime_type]['mt_save'];
		if ( function_exists( $brick_save_method ) && is_callable( $brick_save_method ) ) {
			$content_to_save = call_user_func( $brick_save_method, $values );
		}
		
		// Switch save method
		switch ( $_POST['method'] ) {
			case 'draft':
				$post_status = 'draft';
				break;
			default:
				$post_status = 'publish';
				break;
		}
		
		// Update brick
		$this->save_brick( $_POST['brick_id'], $content_to_save, $post_status, date( 'Y-m-d H:i:s' ) );
		die;
	}
	
	/**
	 * Our bricks need ( law and ) order
	 */
	public function ajax_save_brick_order () {
		// Prepare List
		$list = str_replace( ',', '', $_POST['order']['normal'] );
		$list = explode( 'submitdivsubmitdiv', $list );
		array_pop( $list );
		$order = 1;
		
		// Update Position
		foreach ( $list as $entry ) {
			$brick_id = str_replace( 'brick-', '', $entry );
			$this->update_brick_position( $brick_id, $order );
			++$order;
		}
		die;
	}
	
	/**
	 * Get the next free ID in post table.
	 * Changes AUTO-INCREMENT value.
	 */
	public function ajax_next_free_brick_id () {
		$id = $this->get_next_free_brick_id();
		++$id;
		echo $id;
		die;
	}
	
	/**
	 * Save if a brick is opened or not
	 */
	public function ajax_save_brick_state () {
		// Theses Bricks are closed
		$closed_bricks = explode( ',', $_POST['closed'] );
		foreach ( $closed_bricks as $brick ) {
			$brick_id = str_replace( 'brick-', '', $brick );
			update_post_meta( $brick_id, 'state', 'closed' );
		}
		// These bricks are opened
		$opened_bricks = explode( ',', $_POST['opened'] );
		foreach ( $opened_bricks as $brick ) {
			$brick_id = str_replace( 'brick-', '', $brick );
			update_post_meta( $brick_id, 'state', 'opened' );
		}
		die;
	}
	
	/**
	 * Adds a brick to the brickset
	 */
	public function ajax_add_brick_to_set () {
		$inserted_brick_id = $this->add_brick( $_POST['brick_id'], $_POST['brick_name'], $_POST['brick_slug'], get_current_user_id(), date( 'Y-m-d H:i:s' ), $_POST['brick_slug'], $_POST['set_id'] );
		add_post_meta( $_POST['brick_id'], 'state', 'opened' );
		if ( FALSE === $inserted_brick_id ) {
			brick_system_view::error( 'Brick could not be added!' );
		}
		die;
	}
	
	/**
	 * Update some brick options, esp. name and slug
	 */
	public function ajax_update_brick_options () {
		$this->update_brick( $_POST['brick_id'], $_POST['brick_name'], $_POST['brick_slug'] );
	}
	
	/**
	 * This ajax function adds the brickset to the database
	 * and append the entry on the table
	 */
	function ajax_add_brickset () {
		if ( '' == trim( $_POST['set_slug'] )) {
			$_POST['set_slug'] = strtolower( str_replace( ' ', '-', $_POST['set_name'] ) );
		}
		$inserted_brickset = $this->create_brickset( $_POST['set_name'], $_POST['set_slug'], $_POST['set_desc'], get_current_user_id(), date( 'Y-m-d H:i:s' ) );
		$brickset = $this->get_brickset( $inserted_brickset );
		brick_system_view::prepend_brickset( $brickset, $this->plugin_url );
		die();
	}
	
	/**
	 * This ajax function updates the brickset
	 */
	function ajax_edit_brickset () {
		if ( '' == trim( $_POST['set_name'] ) ) {
			brick_system_view::error( 'Please set a name' );
		}
		else {
			if ( '' == trim( $_POST['set_slug'] )) {
				$_POST['set_slug'] = strtolower( str_replace( ' ', '-', $_POST['set_name'] ) );
			}
			if ( FALSE !== $this->update_brickset( $_POST['set_id'], $_POST['set_name'], $_POST['set_slug'], $_POST['set_desc'] ) ) {
				brick_system_view::error( 'Brickset updated' );
			}
			else {
				brick_system_view::error( 'Brickset could not be updated. Code: onBricksetUpdate' );
			}
		}
		die();
	}
	
	/**
	 * Restores a brick
	 */
	function ajax_restore_brick () {
		$this->restore_brick( $_POST['brick_id'] );
		$brick = $this->get_brick( $_POST['brick_id'] );
		brick_system_view::prepend_brick( $brick );
		die;
	}
	
	/**
	 * This ajax function delete the brickset and its containing bricks
	 */
	function ajax_delete_brickset () {
		$this->remove_brickset( $_POST['brickset'] );
		die();
	}
	
	/**
	 * Deletes a brick
	 */
	function ajax_delete_brick () {
		$this->delete_brick( $_POST['brick_id'] );
		die;
	}
	
	/**
	 * Moves a brick to the trash
	 */
	public function ajax_move_brick_to_trash () {
		$this->move_brick_to_trash( $_POST['brick_id'] );
		$bricks[] = $this->get_brick( $_POST['brick_id'] );
		if ( 'notfound' == $_POST['holder'] ) {
			brick_system_view::show_brick_trash( $bricks, $_POST['set_id'] );
		}
		else {
			brick_system_view::append_trashed_brick( $bricks[0] );
		}
		die;
	}
	
	/**
	 * Emtpy the brickset trash
	 */
	function ajax_empty_brickset_trash () {
		$trashed_bricks = $this->get_trashed_bricks( $_POST['set_id'] );
		$result = '';
		foreach ( $trashed_bricks as $brick ) {
			$this->delete_brick( $brick->ID );
			$result .= $brick->ID . ',';
 		}
 		echo $result;
		die;
	}
	
	/**
	 * This function is just for the picture brick
	 */
	public function ajax_brick_load_pictures () {
		$pictures = $this->get_pictures();
		foreach ( $pictures as $picture ) {
			prepend_picture( $picture, $_GET['brick_id'] );
		}
		die;
	}
	
	/**
	 * The Javascript implemention needs some vars
	 */
	public function localize_vars () {
		$strings = array(
				'save'                 => __( 'Save', 'thbs' ),
				'brick_drafted'        => __( 'Draft', 'thbs' ),
				'update'               => __( 'Update', 'thbs' ),
				'publish'              => __( 'Publish', 'thbs' ),
				'brick_published'      => __( 'Published', 'thbs' ),
				'save_as_draft'        => __( 'Save as Draft', 'thbs' ),
				'enter_url'            => __( 'Enter the full URL of the website', 'thbs' ),
				'brickset_emtpy_trash' => __( 'Do you really want to empty the trash?', 'thbs' ),
				'enter_img_url'        => __( 'Enter the full URL of the image location', 'thbs' ),
				'brickset_delete'      => __( 'Do you really want to delete the brickset?', 'thbs' ),
				'brick_to_trash'       => __( 'Do you really want to move the brick to the trash?', 'thbs' ),
			);
		return $strings;
	}
	
	/**
	 * Check which bricks are in place, require them and write an object list
	 * @return objectlist
	 */
	public function load_bricks () {
		// Some vars
		$i                = 0;
		$this->brick_list = '';
		
		// Little loop to open and read a dir
		$brick_dir = opendir( $this->brick_path );
		while ( $entry = readdir( $brick_dir ) ) {
			if ( '.' != $entry && '..' != $entry && '.svn' != $entry && 'brick.' == substr( $entry, 0, 6 ) ) {
				// require file
				if ( file_exists( $this->brick_path . $entry ) ) {
					require_once $this->brick_path . $entry;
					// Load description function
					$this->description_function = explode( '.php', $entry );
					$this->description_function = explode( 'brick.', $this->description_function[0] );
					$this->description_function = str_replace( '-', '_', $this->description_function[1] ) . '_description';
					if ( function_exists( $this->description_function ) ) {
						// Call description
						if ( function_exists( $this->description_function ) && is_callable( $this->description_function ) ) {
							$this->tmp = call_user_func( $this->description_function );
						}
						
						if ( false == $this->tmp['hide_me'] ) {
							// build array
							$this->brick_list[$this->tmp['slug']] = $this->tmp;
						}
						++$i;
					}
				}
			}
		}
		closedir();
		return $this->brick_list;
	}
	
	/**
	 * load the single brick shortcode
	 */
	public function load_brick_shortcode ( $atts ) {
		$slug = $atts[0];
		return $this->load_brick( $slug, 'return' );
	}
	
	/**
	 * load the brickset shortcode
	 */
	public function load_brickset_shortcode ( $atts ) {
		$slug = $atts[0];
		return $this->load_brickset( $slug, 'return' );
	}
	
	/**
	 * This function loads a single brick
	 * @param string $slug the name of the brick
	 * @param string $type_of_return the type of return. e.g.: echo or return
	 */
	public function load_brick ( $identifier, $type_of_return = null ) {
		if ( is_array( $identifier ) ) {
			$identifier = $identifier[0];
		}
		
		// If the identifier is a number, we now that it is the ID in the post table
		if ( ! is_numeric( $identifier ) ) {
			$brick = $this->get_brick_by_slug( $identifier );
		}
		else {
			$brick = $this->get_brick( $identifier );
		}
		
		// Load the brick from object list and call the frontend method
		if ( 'draft' != $brick->post_status && 'trash' != $brick->post_status ) {
			if ( count( $brick ) >= 1 ) {
				$frontend_funtion = $this->bricks[$brick->post_mime_type]['mt_frontend'];
				if ( function_exists( $frontend_funtion ) && is_callable( $frontend_funtion ) ) {
					$content = call_user_func( $frontend_funtion, $brick->post_content );
					if ( 'return' == $type_of_return ) {
						return do_shortcode( $content );
					}
					else {
						echo do_shortcode( $content );
					}
				}
				else {
					echo 'Function not found: ' . $frontend_funtion;
				}
			}
			else {
				if ( 'return' == $type_of_return ) {
					return 'Brick not found: <code>' . $identifier . '</code>';
				}
				else {
					echo 'Brick not found: <code>' . $identifier . '</code>';
				}
			}
		}
	}
	
	/**
	 * This function loads a whole  brickset
	 * @param string $slug the name of the brickset
	 * @param string $type_of_return the type of return. e.g.: echo or return, in case of null, it echos
	 */
	public function load_brickset ( $slug, $type_of_return = null ) {
		if ( is_array( $slug ) ) {
			$slug = $slug[0];
		}
		$brickset = $this->get_brickset_by_slug( $slug );
		if ( $brickset->ID ) {
			$bricks = $this->get_bricks( $brickset->ID );
			$content = '';
			foreach ( $bricks as $brick ) {
				$content .= $this->load_brick( $brick->ID, $type_of_return );
			}
			
			if ( 'return' == $type_of_return ) {
				return $content;
			}
			else {
				echo $content;
			}
		}
		else {
			if ( 'return' == $type_of_return ) {
				return 'Brickset not found: <code>' . $slug . '</code>';
			}
			else {
				echo 'Brickset not found: <code>' . $slug . '</code>';
			}
		}
	}
}

// Init the brick system
if ( function_exists( 'add_action' ) ) {
	add_action( 'plugins_loaded', array( 'brick_system', 'get_object' ) );
}

// Load frontend function pool
require_once 'php/bricksystem.template.php';

// Load and init the frontend widgets
require_once 'php/widget.brick.php';
require_once 'php/widget.brickset.php';
if ( function_exists( 'register_widget' ) ) {
	function bricksystem_widget_brick_start () {
		return register_widget( 'bricksystem_widget_brick' );
	}
	function bricksystem_widget_brickset_start () {
		return register_widget( 'bricksystem_widget_brickset' );
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'widgets_init', 'bricksystem_widget_brick_start' );
	add_action( 'widgets_init', 'bricksystem_widget_brickset_start' );
}

/** CODEBUSTERS ****************** http://projektmotor.de **
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMNy//dMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMms+.    //NMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMM/`   . `shhhdddmNMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMs .- `:  /yyyyyyyyyhdNMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMmy   `  .  .hyyyyyyyyyyyhdMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMmh/     .`    `hmmmdhyyyyyyyydMMMMMMMMMMMMMMM
MMMMMMMMMMMMMNhh+      .      -hooshmmhyyyyyyyNMMMMMMMMMMMMM
MMMMMMMMMMMMNhyh+     `m.     -    `/hhhhhhhhhymMMMMMMMMMMMM
MMMMMMMMMMMNhyyyh/     `.     ` `:shhhhhhhhhhhho/:mMMMMMMMMM
MMMMMMMMMMMdyyys.             :ohhhhhhhhhhs++:  +NMMMMMMMMMM
MMMmmMMMMMNhyh+            -+yyyyyyhhhho:      -+sydNMMMMMMM
MMms/`-+ymNh+`          .+yhhhhhhhyhs/-`        :+o+dMMMMMMM
MMMs.     ``      `` `/ydddddddddy/`  - ``-+oss/-/NMMMMMMMMM
MMMmN/`        .+dMmsdddddddddh+.     ``  :ddhhhyNMMMMMMMMMM
MMMMmymmyo+` +yddddddddddddho-           `hddddddMMMMMMMMMMM
MMMMMMMMMMMNsydddddddddddd/             .hddddddNMMMMMMMMMMM
MMMMMMMMMMMMNmmmmmmmmdmNm: ``         `+dmmmmmdNMMMMMMMMMMMM
MMMMMMMMMMMMMMmdddddddms`          `:oddddddddNMMMMMMMMMMMMM
MMMMMMMMMMMMMMMNddddddddhys++//++sydddddddddmMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMNdhhhhhhhhhhhhhhhhhhhhhhdmMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMmdhhhhhhhhhhhhhhhhdmNMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMNmmmdddddmmNNMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMM WHO DO YOU GONNA CALL? MMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
** http://inpsyde.com ********** http://hughwillfayle.de **/