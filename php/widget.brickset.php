<?php
/**
 * Brick System - Widget - Brickset Widget
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Widgets
 */

class bricksystem_widget_brickset extends WP_Widget {
    function bricksystem_widget_brickset () {
    	// Set options
    	$widget_name    = __( 'Bricksyten Brickset Widget', 'thbs' );
    	$widget_width   = 300;
    	$widget_options = array( 'description' => __( 'This widget displays the whole brickset.', 'thbs'),
    							 'width'       => $widget_width );
    	$this->brick_system = new brick_system( false );
        parent::WP_Widget( false, $widget_name, $widget_width, $widget_options );
    }

    function widget( $args, $instance ) {
    	extract( $args );
    	$list  = esc_attr( $instance['listtype'] );
        $set   = esc_attr( $instance['brickset'] );
    	$pages = explode( ',', $instance['pages'] );
    	
    	if ( ('whitelist' == $list && in_array( get_the_ID(), $pages ) ) || ( 'blacklist' == $list && !in_array( get_the_ID(), $pages ) ) || 'everywhere' == $list ) {
    		// echo the widget
    		echo $before_widget;
    			the_brickset( $set );
    		echo $after_widget;
    	}
    }
    
    function update ( $new_instance, $old_instance ) {				
		$instance             = $old_instance;
		$instance['pages']    = $new_instance['pages'];
		$instance['listtype'] = $new_instance['listtype'];
		$instance['brickset'] = $new_instance['brickset'];
        return $instance;
    }

    function form ( $instance ) {
        $bricksets = $this->brick_system->get_bricksets();
    	$pages     = esc_attr( $instance['pages'] );
        $list      = esc_attr( $instance['listtype'] );
        $set       = esc_attr( $instance['brickset'] );
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'brickset' ); ?>"><?php _e( 'Choose Brickset', 'thbs' )?><br /></label>
                <select id="<?php echo $this->get_field_id( 'brickset' ); ?>" name="<?php echo $this->get_field_name( 'brickset' ); ?>">
                    <?php foreach ( $bricksets as $brickset ) { ?>
                    <option value="<?php echo $brickset->post_name; ?>" <?php echo ( $brickset->post_name == $set ? 'selected="selected"' : '' ); ?>><?php echo $brickset->post_title; ?></option>
                    <?php } ?>
                </select>
            </p>
        	<p>
                <label for="<?php echo $this->get_field_id( 'listtype' ); ?>"><?php _e( 'Appereance', 'thbs' )?><br /></label>
                <input type="radio" id="<?php echo $this->get_field_id( 'listtype' ); ?>" name="<?php echo $this->get_field_name( 'listtype' ); ?>" value="blacklist" <?php echo ('blacklist' == $list ? ' checked="checked"' : '') ?> /> <?php _e( 'Widget doesnot appear on this pages', 'thbs' ); ?><br />
                <input type="radio" id="<?php echo $this->get_field_id( 'listtype' ); ?>" name="<?php echo $this->get_field_name( 'listtype' ); ?>" value="whitelist" <?php echo ('whitelist' == $list ? ' checked="checked"' : '') ?> /> <?php _e( 'Widget only appears on this pages', 'thbs' ); ?><br />
                <input type="radio" id="<?php echo $this->get_field_id( 'listtype' ); ?>" name="<?php echo $this->get_field_name( 'listtype' ); ?>" value="everywhere" <?php echo ('everywhere' == $list ? ' checked="checked"' : '') ?> /> <?php _e( 'Widget appears everywhere', 'thbs' ); ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'pages' ); ?>"><?php _e( 'Page-IDs', 'thbs' ); ?>: <small><?php echo _e( 'comma sperated', 'thbs' ); ?></small></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'pages' ); ?>" name="<?php echo $this->get_field_name( 'pages' ); ?>" value="<?php echo $pages; ?>" />
            </p>
        <?php 
    }
}