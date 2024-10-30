<?php
/**
 * Brick System
 * @license CC-BY-SA-NC
 * @package Wordpress Brick System
 * @subpackage Model
 */

class brick_system_model {
	public function create_brickset ( $name, $slug, $description, $author, $date ) {
		global $wpdb;
		$this->data = array(
				'post_title'   => $wpdb->escape( $name ),
				'post_name'    => $wpdb->escape( $slug ),
				'post_excerpt' => $wpdb->escape( $description ),
				'post_author'  => $wpdb->escape( $author ),
				'post_date'    => $wpdb->escape( $date ),
				'post_type'    => 'brickset',
			);
		$wpdb->insert( $wpdb->posts, $this->data );
		return $wpdb->insert_id;
	}
	
	public function update_brickset ( $set_id, $name, $slug, $description ) {
		global $wpdb;
		$this->data = array(
				'post_title'   => $wpdb->escape( $name ),
				'post_name'    => $wpdb->escape( $slug ),
				'post_excerpt' => $wpdb->escape( $description ),
			);
		$this->where = array(
				'ID' => $wpdb->escape( $set_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public function get_bricksets () {
		global $wpdb;
		$res = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "posts WHERE
										`post_type` = 'brickset' ORDER BY `ID` DESC" );
		return $res;
	}
	
	public function get_brickset ( $brickset ) {
		global $wpdb;
		$set_id = $wpdb->escape( $brickset );
		$res    = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `ID` = "' . $set_id . '"' );
		return $res[0];
	}
	
	public function remove_brickset ( $brickset ) {
		global $wpdb;
		$brickset = $wpdb->escape( $brickset );
		$query = $wpdb->prepare( 'DELETE FROM '. $wpdb->posts . ' WHERE `ID` = "' . $brickset . '"' );
		$wpdb->query( $query );
		$query = $wpdb->prepare( 'DELETE FROM '. $wpdb->posts . ' WHERE `post_parent` = "' . $brickset . '"' );
		$wpdb->query( $query );
	}
	
	public function delete_brick ( $brick_id ) {
		global $wpdb;
		$brick_id = $wpdb->escape( $brick_id );
		$query = $wpdb->prepare( 'DELETE FROM '. $wpdb->posts . ' WHERE `ID` = "' . $brick_id . '"' );
		$wpdb->query( $query );
		delete_post_meta( $brick_id, '' );
	}
	
	public function add_brick ( $brick_id, $brick_title, $brick_name, $author, $date, $brick_slug, $set_id ) {
		global $wpdb;
		$this->data = array(
				'ID'              => $wpdb->escape( $brick_id ),
				'post_title'      => $wpdb->escape( $brick_title ),
				'post_name'       => $wpdb->escape( $brick_name ),
				'post_author'     => $wpdb->escape( $author ),
				'post_date'       => $wpdb->escape( $date ),
				'post_mime_type'  => $wpdb->escape( $brick_slug ),
				'post_parent'     => $wpdb->escape( $set_id ),
				'post_status'     => 'draft',
				'post_type'       => 'brick',
			);
		$wpdb->insert( $wpdb->posts, $this->data );
		return $wpdb->insert_id;
	}
	
	public function update_brick ( $brick_id, $brick_name, $brick_slug ) {
		global $wpdb;
		$this->data = array(
				'post_title'   => $wpdb->escape( $brick_name ),
				'post_name'    => $wpdb->escape( $brick_slug ),
			);
		$this->where = array(
				'ID' => $wpdb->escape( $brick_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public function update_brick_position ( $brick_id, $position ) {
		global $wpdb;
		$this->data = array(
				'menu_order' => $wpdb->escape( $position ),
			);
		$this->where = array(
				'ID' => $wpdb->escape( $brick_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public function move_brick_to_trash ( $brick_id ) {
		global $wpdb;
		$this->data = array(
				'post_status' => 'trash',
			);
		$this->where = array(
				'ID' => $wpdb->escape( $brick_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public function restore_brick ( $brick_id ) {
		global $wpdb;
		$this->data = array(
				'post_status' => 'draft',
			);
		$this->where = array(
				'ID' => $wpdb->escape( $brick_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public function get_brick ( $brick_id ) {
		global $wpdb;
		$brick_id = $wpdb->escape( $brick_id );
		$res      = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `ID` = "' . $brick_id . '"' );
		return $res[0];
	}
	
	public function get_bricks ( $set_id ) {
		global $wpdb;
		$set_id = $wpdb->escape( $set_id );
		$res    = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_parent` = "' . $set_id . '" AND `post_status` != "trash" ORDER BY `menu_order` ASC' );
		return $res;
	}
	
	public function get_published_bricks ( $set_id ) {
		global $wpdb;
		$set_id = $wpdb->escape( $set_id );
		$res    = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_parent` = "' . $set_id . '" AND `post_status` = "publish" ORDER BY `menu_order` ASC' );
		return $res;
	}
	
	public function get_all_bricks () {
		global $wpdb;
		$res    = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_type` = "brick" AND `post_status` = "publish" ORDER BY `menu_order` ASC' );
		return $res;
	}
	
	public function get_trashed_bricks ( $set_id ) {
		global $wpdb;
		$set_id = $wpdb->escape( $set_id );
		$res    = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_parent` = "' . $set_id . '" AND `post_status` = "trash" ORDER BY `menu_order` ASC' );
		return $res;
	}
	
	public function get_brick_by_slug ( $slug ) {
		global $wpdb;
		$slug = $wpdb->escape( $slug );
		$res = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_name` = "' . $slug . '" AND `post_type` = "brick"' );
		return $res[0];
	}
	
	public function get_brickset_by_slug ( $slug ) {
		global $wpdb;
		$slug = $wpdb->escape( $slug );
		$res = $wpdb->get_results( 'SELECT ID FROM '. $wpdb->posts . ' WHERE `post_name` = "' . $slug . '" AND `post_type` = "brickset"' );
		return $res[0];
	}
	
	public function save_brick ( $brick_id, $post_content, $post_status, $date ) {
		global $wpdb;
		$this->data = array(
				'post_content'      => $wpdb->escape( $post_content ),
		 		'post_status'       => $wpdb->escape( $post_status ),
				'post_modified'     => $wpdb->escape( $date ),
				'post_modified_gmt' => $wpdb->escape( $date ),
			);
		$this->where = array(
				'ID' => $wpdb->escape( $brick_id ),
			);
		return $wpdb->update( $wpdb->posts, $this->data, $this->where );
	}
	
	public static function get_next_free_brick_id () {
		global $wpdb;
		$res = $wpdb->get_results( 'SELECT ID FROM '. $wpdb->posts . ' ORDER BY ID DESC LIMIT 0,1' );
		$alter = $wpdb->query( 'ALTER TABLE '. $wpdb->posts . ' AUTO_INCREMENT = ' . $res[0]->ID );
		return $res[0]->ID;
	}
	
	public function get_pictures () {
		global $wpdb;
		$res = $wpdb->get_results( 'SELECT * FROM '. $wpdb->posts . ' WHERE `post_type` = "attachment" AND `post_mime_type` LIKE "%image%" ORDER BY ID DESC' );
		return $res;
	}
}