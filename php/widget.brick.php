<?php
/**
 * Brick System - Widget - Single Brick Widget
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage Widgets
 */

class bricksystem_widget_brick extends WP_Widget {
	function bricksystem_widget_brick () {
		// Set options
		$widget_name    = __( 'Bricksyten Brick Widget', 'thbs' );
		$widget_width   = 300;
		$widget_options = array( 'description' => __( 'This widget displays the brick.', 'thbs'),
								 'width'       => $widget_width );
		$this->brick_system = new brick_system( false );
		parent::WP_Widget( false, $widget_name, $widget_width, $widget_options );
	}

    function widget( $args, $instance ) {
		extract( $args );
		$list  = esc_attr( $instance['listtype'] );
		$brick = esc_attr( $instance['brick'] );
		$pages = explode( ',', $instance['pages'] );
    	
		if ( ('whitelist' == $list && in_array( get_the_ID(), $pages ) ) || ( 'blacklist' == $list && !in_array( get_the_ID(), $pages ) ) || 'everywhere' == $list ) {
			// echo the widget
			echo $before_widget;
				the_brick( $brick );
			echo $after_widget;
		}
	}
    
	function update ( $new_instance, $old_instance ) {				
		$instance             = $old_instance;
		$instance['brick']    = $new_instance['brick'];
		$instance['pages']    = $new_instance['pages'];
		$instance['listtype'] = $new_instance['listtype'];
		return $instance;
	}

	function form ( $instance ) {
		$bricks = $this->brick_system->get_all_bricks();
		$brick  = esc_attr( $instance['brick'] );
		$pages  = esc_attr( $instance['pages'] );
		$list   = esc_attr( $instance['listtype'] );
		?>
            <p>
                <label for="<?php echo $this->get_field_id( 'brick' ); ?>"><?php _e( 'Choose Brick', 'thbs' )?><br /></label>
                <select id="<?php echo $this->get_field_id( 'brick' ); ?>" name="<?php echo $this->get_field_name( 'brick' ); ?>">
                    <option value="0"><?php _e( 'Choose Brick', 'thbs' )?></option>
                    <option value="0">---</option>
                    <?php foreach ( $bricks as $the_brick ) { ?>
                    <option value="<?php echo $the_brick->ID; ?>" <?php echo ( $the_brick->ID == $brick ? 'selected="selected"' : '' ); ?>><?php echo $the_brick->post_title; ?></option>
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