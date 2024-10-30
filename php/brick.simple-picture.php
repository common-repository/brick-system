<?php
/**
 * Brick System - Brick - Simple Picture
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Brick
 */

function simple_picture_description () {
	$arr = array(
		'name'        => 'Simple Picture',
		'slug'		  => 'simple_picture',
		'description' => 'Brick to display a picture',
		'version'     => '0.1',
		'author'      => 'Thomas Herzog',
		'url'         => 'http://hughwillfayle.de/wordpress/brick-system',
		'licence'     => 'CC-BY-SA-NC',
		'mt_frontend' => 'simple_picture_frontend',
		'mt_backend'  => 'simple_picture_backend',
		'mt_save'	  => 'simple_picture_save',
	);
	return $arr;
}

function simple_picture_backend ( $brick ) {
	$data = stripcslashes( $brick->post_content );
	$data = unserialize( $data );
	?>
    <div class="alignright picture_chooser_container"><a id="picture_chooser_link" href="javascript:bs_sp.picture_chooser(<?php echo $brick->ID; ?>);" class="picture_chooser_link closed"><?php _e( 'Choose picture from mediathek', 'thbs' ); ?></a></div><br class="clear" />
    <div id="picture_chooser_<?php echo $brick->ID; ?>" class="brick-options picture_chooser"></div>
    
    <table class="slidetoggle describe" style="display: table;">
        <tbody>
            <tr class="url">
                <th valign="top" scope="row" class="label"><label for="image_url"><span class="alignleft"><?php _e( 'Image Location', 'thbs' ); ?></span></label></th>
                <td class="field"><input type="text" class="text urlfield" name="image_url" value="<?php echo $data['image_url']; ?>" /><br></td>
            </tr>
	        <tr class="post_title">
	            <th valign="top" scope="row" class="label"><label for="image_title"><span class="alignleft"><?php _e( 'Title' ); ?></span></label></th>
	            <td class="field"><input type="text" class="text" id="image_title" name="image_title" value="<?php echo $data['image_title']; ?>" /></td>
	        </tr>
	        <tr class="image_alt">
	            <th valign="top" scope="row" class="label"><label for="image_alt"><span class="alignleft"><?php _e( 'Description' ); ?></span></label></th>
	            <td class="field"><input type="text" class="text" id="image_alt" name="image_alt" value="<?php echo $data['image_alt']; ?>" /><p class="help"><?php _e( 'Description of the picture, e.g. Mona Lisa' ,'thbs' ); ?></p></td>
	        </tr>
            <tr class="image_alt">
                <th valign="top" scope="row" class="label"><label for="image_style"><span class="alignleft"><?php _e( 'Stylesheet', 'thbs' ); ?></span></label></th>
                <td class="field"><input type="text" class="text" id="image_style" name="image_style" value="<?php echo $data['image_style']; ?>" /></td>
            </tr>
            <tr class="image_alt">
                <th valign="top" scope="row" class="label"><label for="image_class"><span class="alignleft"><?php _e( 'CSS-Class', 'thbs' ); ?></span></label></th>
                <td class="field"><input type="text" class="text" id="image_class" name="image_class" value="<?php echo $data['"image_class"']; ?>" /></td>
            </tr>
            <tr class="image_alt">
                <th valign="top" scope="row" class="label"><label for="image_class"><span class="alignleft"><?php _e( 'Show Style-Box', 'thbs' ); ?></span></label></th>
                <td class="field"><input type="checkbox" name="image_box" id="image_box" <?php echo ( 'on' == $data['image_box'] ? 'checked="checked"' : "" ); ?> /></td>
            </tr>
	        <tr class="align">
	            <th valign="top" scope="row" class="label"><label for="image_align"><span class="alignleft"><?php _e( 'Alignment' ); ?></span></label></th>
	            <td class="field">
                    <input <?php echo ( 'none' == $data['image_align'] ? 'checked="checked"' : "" ); ?> type="radio" name="image_align" id="image_align_none" class="radio" value="none" /><label for="image_align_none" class="align image-align image-align-none-label"><?php _e( 'None' ); ?></label>
					<input <?php echo ( 'left' == $data['image_align'] ? 'checked="checked"' : "" ); ?> type="radio" name="image_align" id="image_align_left" class="radio" value="left" /><label for="image_align_left" class="align image-align image-align-left-label"><?php _e( 'Left' ); ?></label>
					<input <?php echo ( 'center' == $data['image_align'] ? 'checked="checked"' : "" ); ?> type="radio" name="image_align" id="image_align_center" class="radio" value="center" /><label for="image_align_center" class="align image-align image-align-center-label"><?php _e( 'Center' ); ?></label>
					<input <?php echo ( 'right' == $data['image_align'] ? 'checked="checked"' : "" ); ?> type="radio" name="image_align" id="image_align_right" class="radio" value="right" /><label for="image_align_right" class="align image-align image-align-right-label"><?php _e( 'Right' ); ?></label>
                </td>
	        </tr>
        </tbody>
    </table>
    <?php
}

function simple_picture_frontend ( $string ) {
	$data = unserialize( stripcslashes( $string ) );
	
	$image_alt = '';
	if ( '' != trim( $data['image_alt'] ) ) {
		$image_alt = ' alt="' . $data['image_alt'] . '"';
	}
	$image_style = '';
	if ( '' != trim( $data['image_style'] ) ) {
		$image_style = ' style="' . $data['image_style'] . '"';
	}
	$image_align = '';
	if ( '' != trim( $data['image_align'] ) ) {
		$image_align = ' align="' . $data['image_align'] . '"';
	}
	$image_string = '<img src="' . $data['image_url'] . '"' . $image_align . $image_alt . $image_class . $image_style . ' />';
	
	if ( 'on' == $data['image_box'] ) {
		if ( '' != trim( $data['image_align'] ) ) {
			$box_align = ' float: ' . $data['image_align'];
		}
		$return_string  = '<div style="border: 1px solid #999; background: #f3f3f3; padding: 7px; margin: 3px;' . $box_align . '">';
		$return_string .= $image_string;
		if ( '' != trim( $data['image_alt'] ) ) {
			$return_string .= '<p align="center">' . $data['image_alt'] . '</p>';
		}
		$return_string .= '</div>';
	}
	else {
		$return_string = $image_string;
	}
	
	return $return_string;
}

function simple_picture_save ( $data ) {
	if ( '' == trim( $data['image_align'] ) ) {
		$data['image_align'] = 'none';
	}
	$string = serialize( $data );
	return $string;
}

function prepend_picture ( $picture, $brick_id ) {
	?>
        <div id="media-item" class="media-item preloaded">
            <?php echo wp_get_attachment_image( $picture->ID, array( 30, 30 ), false, array( 'class' => 'pinkynail toggle' ) ); ?>
            <a class="toggle describe-toggle-on" href="javascript:bs_sp.take_picture(<?php echo $brick_id ?>, '<?php echo $picture->guid; ?>');"><?php _e( 'Take it', 'thbs' ); ?></a>
            <div class="filename new"><span class="title"><?php echo $picture->post_title; ?></span></div>
        </div>
    <?php
}