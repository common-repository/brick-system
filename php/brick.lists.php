<?php
/**
 * Brick System - Brick - Lists
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Brick
 */

function lists_description () {
	$arr = array(
		'name'        => 'Lists',
		'slug'		  => 'lists',
		'description' => 'This brick provides manages lists.',
		'version'     => '0.1',
		'author'      => 'Thomas Herzog',
		'url'         => 'http://hughwillfayle.de/wordpress/brick-system',
		'licence'     => 'CC-BY-SA-NC',
		'mt_frontend' => 'lists_frontend',
		'mt_backend'  => 'lists_backend',
		'mt_save'	  => 'lists_save',
		'hide_me'     => true
	);
	return $arr;
}

function lists_frontend ( $data ) {

	print '<pre>';
	print_r( $data );
	print '</pre>';
	
	return $content;
}

function lists_backend ( $brick ) {
	$data = unserialize( stripslashes( $brick->post_content ) );
	if ( count( $data['elements'] ) > 0 ) {
		$elements = unserialize( stripslashes( $data['elements'] ) );
	}
	?>
    <table class="slidetoggle describe" style="display: table;">
        <tbody>
            <tr class="align">
                <th valign="top" scope="row" class="label"><label for="type"><span class="alignleft"><?php _e( 'Typ' ); ?></span></label></th>
                <td class="field">
                    <input <?php echo ( 'ul' == $data['type'] ? 'checked="checked"' : "" ); ?> type="radio" name="type" id="type_ul" class="radio" value="ul" /><label for="type_ul" class="align"><?php _e( 'Standard List (ul)', 'thbs' ); ?></label>
                    <input <?php echo ( 'ol' == $data['type'] ? 'checked="checked"' : "" ); ?> type="radio" name="type" id="type_ol" class="radio" value="ol" /><label for="type_ol" class="align"><?php _e( 'Numeric List (ol)', 'thbs' ); ?></label>
                </td>
            </tr>
            <tr class="align">
                <th valign="top" scope="row" class="label"><label for="css_class"><?php _e( 'CSS-Class', 'thbs' ); ?>:</label></th>
                <td class="field">
				    <input type="text" name="css_class" value="<?php echo $data['css_class']; ?>" />
                </td>
            </tr>
            <tr class="align">
                <th valign="top" scope="row" class="label"><label for="stylesheet"><?php _e( 'Stylesheet', 'thbs' ); ?>:</label></th>
                <td class="field">
                    <input type="text" name="stylesheet" value="<?php echo $data['stylesheet']; ?>" />
                </td>
            </tr>
            <tr class="align">
                <th valign="top" scope="row" class="label"><label for="stylesheet"><?php _e( 'Elements', 'thbs' ); ?>:</label></th>
                <td class="field" id="elements">
                    <?php if ( ! $i ) $i = 0; ?>
                    <?php foreach ( $elements as $element ) { ?>
                        <input class="elements" type="text" name="elements_<?php echo $i; ?>" id="elements_<?php echo $i; ?>" value="<?php echo $element; ?>" />
                        <img src='<?php echo plugin_dir_url( __FILE__ ); ?>../images/bullet_add.png' alt="<?php _e( 'Add element after this.', 'thbs' ); ?>" />
                        <img src='<?php echo plugin_dir_url( __FILE__ ); ?>../images/bullet_delete.png' alt="<?php _e( 'Remove this element.', 'thbs' ); ?>" />
                        <?php ++$i; ?>
                    <?php } ?>
                    <input class="elements" type="text" name="elements_<?php echo $i; ?>" id="elements_<?php echo $i; ?>" />
                    <img src='<?php echo plugin_dir_url( __FILE__ ); ?>../images/bullet_add.png' alt="<?php _e( 'Add element after this.', 'thbs' ); ?>" />
                    <img src='<?php echo plugin_dir_url( __FILE__ ); ?>../images/bullet_delete.png' alt="<?php _e( 'Remove this element.', 'thbs' ); ?>" />
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}

function lists_save ( $data ) {
	
	// Get data
	foreach ( $data as $key => $value ) {
		// Check count
		if ( ! $i ) {
			$i = 0;
		}
		
		// load elements into array an kill original
		if ( stristr( $key, 'elements_' ) ) {
			$data['elements'][$i] = $value;
			unset( $data[$key] );
			++$i;
		}
	}
	
	// serialize everything
	$data['elements'] = serialize( $data['elements'] );
	$content = serialize( $data );
	
	return $content;
}