<?php
/**
 * Brick System - Brick - Simple Text
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Brick
 */

function simple_text_description () {
	$arr = array(
		'name'        => 'Simple Text',
		'slug'		  => 'simple_text',
		'description' => 'Simple text brick with no rich text editing tools.',
		'version'     => '0.2',
		'author'      => 'Thomas Herzog',
		'url'         => 'http://hughwillfayle.de/wordpress/brick-system',
		'licence'     => 'CC-BY-SA-NC',
		'mt_frontend' => 'simple_text_frontend',
		'mt_backend'  => 'simple_text_backend',
		'mt_save'	  => 'simple_text_save',
	);
	return $arr;
}

function simple_text_frontend ( $data ) {
	$content = explode( '##!!##!!##', $data );
	$text = stripcslashes( $content[0] );
	$automatic = $content[1];
	
	if ( 'on' == $automatic ) {
		$text = wpautop( $text );
	}
	
	return $text;
}

function simple_text_backend ( $brick ) {
	$data = $brick->post_content;
	$content = explode( '##!!##!!##', $data );
	$text = stripcslashes( $content[0] );
	$automatic = $content[1];
	?>
    <div class="postarea">
	    <div id="quicktags">
	        <div id="ed_toolbar">
                <input type="button" id="ed_strong" accesskey="b" class="ed_button ed_b_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'b' );" value="b" />
                <input type="button" id="ed_em" accesskey="i" class="ed_button ed_i_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'i' );" value="i" />
                <input type="button" id="ed_underline" accesskey="u" class="ed_button ed_u_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'u' );" value="u" />
                <input type="button" id="ed_del" accesskey="d" class="ed_button ed_strike_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'strike' );" value="strike" />
                &nbsp;
                <input type="button" id="ed_link" accesskey="a" class="ed_button ed_a_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'a' );" value="a" />
                <input type="button" id="ed_img" accesskey="m" class="ed_button ed_img_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'img' );" value="img" />
                <input type="button" id="ed_block" accesskey="q" class="ed_button ed_bquote_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'bquote' );" value="blockquote" />
                <input type="button" id="ed_code" accesskey="c" class="ed_button ed_code_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'code' );" value="code" />
                &nbsp;
                <input type="button" id="ed_ul" accesskey="u" class="ed_button ed_ul_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'ul' );" value="ul" />
                <input type="button" id="ed_ol" accesskey="o" class="ed_button ed_ol_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'ol' );" value="ol" />
                <input type="button" id="ed_li" accesskey="l" class="ed_button ed_li_<?php echo $brick->ID; ?>" onclick="bs_st.insert_tag( <?php echo $brick->ID; ?>, 'li' );" value="li" />
            </div>
	    </div>
        <input type="hidden" id="brick_id" name="brick_id" value="<?php echo $brick->ID; ?>" />
	    <textarea rows="15" cols="40" name="content_<?php echo $brick->ID; ?>" tabindex="2" id="content_<?php echo $brick->ID; ?>" class="full no_editor"><?php echo $text; ?></textarea><br />
    </div>
    <input type="checkbox" name="automatic" <?php echo ( 'on' == $automatic ? 'checked="checked"' : "" ); ?> /> <?php _e( 'Automatically add paragraphs' ); ?>
    <?php
}

function simple_text_save ( $data ) {
	if ( 'on' == $data['automatic'] ) {
		$automatic = '##!!##!!##on';
	}
	$content = $data['content_' . $data['brick_id']] . $automatic;
	return $content;
}