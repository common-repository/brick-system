<?php

/**
 * Brick System
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Template Functions
 */

function the_brick ( $slug ) {
	$brick_system = new brick_system();
	$brick_system->load_brick( $slug, 'echo' );
}

function get_the_brick ( $slug ) {
	$brick_system = new brick_system();
	return $brick_system->load_brick( $slug, 'return' );
}

function the_brickset ( $slug ) {
	$brick_system = new brick_system();
	$brick_system->load_brickset( $slug, 'echo' );
}

function get_the_brickset ( $slug ) {
	$brick_system = new brick_system();
	return $brick_system->load_brickset( $slug, 'return' );
}