<?php
/**
 * Brick System - Brick - Spacer
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Brick
 */

function spacer_description () {
	$arr = array(
		'name'        => 'Spacer',
		'slug'		  => 'spacer',
		'description' => 'Brick to display a spacer',
		'version'     => '0.1',
		'author'      => 'Thomas Herzog',
		'url'         => 'http://hughwillfayle.de/wordpress/brick-system',
		'licence'     => 'CC-BY-SA-NC',
		'mt_frontend' => 'spacer_frontend',
		'mt_backend'  => 'spacer_backend',
		'mt_save'	  => 'spacer_save',
	);
	return $arr;
}

function spacer_backend ( $brick ) {
	$data = $brick->post_content;
	$data = explode( '&', $data );
	foreach ( $data as $value ) {
		$type = explode( '=', $value );
		if ( 'class' == $type[0] && '' != trim( $type[1] ) ) {
			$class = $type[1];
		}
		if ( 'style' == $type[0] && '' != trim( $type[1] ) ) {
			$style = $type[1];
		}
	}
	?>
    <label for="css_class"><?php _e( 'CSS-Class', 'thbs' ); ?>:</label>
    <input type="text" name="css_class" value="<?php echo $class; ?>" /><br />
    <label for="stylesheet"><?php _e( 'Stylesheet', 'thbs' ); ?>:</label>
    <input type="text" name="stylesheet" value="<?php echo $style; ?>" />
    <?php
}

function spacer_frontend ( $data ) {
	$data = explode( '&', $data );
	foreach ( $data as $value ) {
		$type = explode( '=', $value );
		if ( 'class' == $type[0] && '' != trim( $type[1] ) ) {
			$class = ' class="' . $type[1] . '"';
		}
		if ( 'style' == $type[0] && '' != trim( $type[1] ) ) {
			$style = ' style="' . $type[1] . '"';
		}
	}
	$string = '<hr '. $class . $style .'/>';
	
	return $string;
}

function spacer_save ( $data ) {
	$string = 'class=' . $data['css_class'] . '&style=' . $data['stylesheet'];
	return $string;
}