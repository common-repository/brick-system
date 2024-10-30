<?php
/**
 * Brick System - Brick - Headlines
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Brick
 */

function headlines_description () {
	$arr = array(
		'name'        => 'Headlines',
		'slug'		  => 'headlines',
		'description' => 'Brick to display headlines',
		'version'     => '0.1',
		'author'      => 'Thomas Herzog',
		'url'         => 'http://hughwillfayle.de/wordpress/brick-system',
		'licence'     => 'CC-BY-SA-NC',
		'mt_frontend' => 'headlines_frontend',
		'mt_backend'  => 'headlines_backend',
		'mt_save'	  => 'headlines_save'
	);
	return $arr;
}

function headlines_backend ( $brick ) {
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
		if ( 'headline_type' == $type[0] && '' != trim( $type[1] ) ) {
			$headline_type = $type[1];
		}
		if ( 'headline' == $type[0] && '' != trim( $type[1] ) ) {
			$headline = stripslashes( $type[1] );
		}
	}
	?>
    <label for="headline_type"><?php _e( 'Headline-Type', 'thbs' ); ?>:</label>
    <select name="headline_type">
        <option value="1" <?php echo ( 1 == $headline_type ? 'selected="selected"' : '' ); ?>>h1</option>
        <option value="2" <?php echo ( 2 == $headline_type ? 'selected="selected"' : '' ); ?>>h2</option>
        <option value="3" <?php echo ( 3 == $headline_type ? 'selected="selected"' : '' ); ?>>h3</option>
        <option value="4" <?php echo ( 4 == $headline_type ? 'selected="selected"' : '' ); ?>>h4</option>
        <option value="5" <?php echo ( 5 == $headline_type ? 'selected="selected"' : '' ); ?>>h5</option>
        <option value="6" <?php echo ( 6 == $headline_type ? 'selected="selected"' : '' ); ?>>h6</option>
    </select><br />
    <label for="headline"><?php _e( 'Content' ); ?>:</label>
    <input type="text" name="headline" value='<?php echo $headline; ?>' /><br />
    <label for="css_class"><?php _e( 'CSS-Class', 'thbs' ); ?>:</label>
    <input type="text" name="css_class" value="<?php echo $class; ?>" /><br />
    <label for="stylesheet"><?php _e( 'Stylesheet', 'thbs' ); ?>:</label>
    <input type="text" name="stylesheet" value="<?php echo $style; ?>" />
    <?php
}

function headlines_frontend ( $data ) {
	$data = explode( '&', $data );
	foreach ( $data as $value ) {
		$type = explode( '=', $value );
		if ( 'class' == $type[0] && '' != trim( $type[1] ) ) {
			$class = ' class="' . $type[1] . '"';
		}
		if ( 'style' == $type[0] && '' != trim( $type[1] ) ) {
			$style = ' style="' . $type[1] . '"';
		}
		if ( 'headline_type' == $type[0] && '' != trim( $type[1] ) ) {
			$headline_type = $type[1];
		}
		if ( 'headline' == $type[0] && '' != trim( $type[1] ) ) {
			$headline = stripslashes( $type[1] );
		}
	}
	$string = '<h' . $headline_type . $class . $style . '>' . $headline . '</h' . $headline_type . '>';
	return $string;
}

function headlines_save ( $data ) {
	$string = 'headline_type=' . $data['headline_type'] . '&headline=' . $data['headline'] . '&' . 'class=' . $data['css_class'] . '&style=' . $data['stylesheet'];;
	return $string;
}