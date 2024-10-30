<?php
/**
 * Brick System
 * @license CC-BY-SA-NC
 * @package WordPress Brick System
 * @subpackage View
 */

class brick_system_view {
	public static function wrapper_start () {
		?>
		<div class="wrap nosubsub">
			<div id="icon-themes" class="icon32"><br></div>
			<h2>
				<?php _e( 'Bricksets', 'thbs' ); ?>
			</h2>
			<div id="ajax-response"></div>
		<?php
	}
	
	/**
	 * Error Message, text_domain is set here
	 * @param string $error
	 */
	public function error ( $error ) {
		?>
		<div class="error"><p><?php _e( $error, 'thbs' ); ?></p></div>
		<?php
	}
	
	public function update ( $msg ) {
		?>
		<div class="updated"><p><?php _e( $msg, 'thbs' ); ?></p></div>
		<?php
	}
	
	public static function wrapper_end () {
		?>
		</div>
		<?php
	}
	
	public static function manage_bricksets ( $bricksets, $plugin_url ) {
		?>
			<div id="col-container"> 
				
				<!-- Bricksets -->
				<div id="col-right"> 
					<div class="col-wrap"> 
						 
						<div class="clear"></div> 
						<table class="widefat tag fixed" cellspacing="0"> 
							<thead> 
								<tr> 
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th> 
									<th scope="col" id="name" class="manage-column column-name" style=""><?php _e( 'Name' ); ?></th> 
									<th scope="col" id="description" class="manage-column column-description" style=""><?php _ex( 'Description', 'Taxonomy Description' ); ?></th> 
									<th scope="col" id="slug" class="manage-column column-slug" style=""><?php _e( 'Slug', 'Taxonomy Slug' ); ?></th> 
								</tr> 
							</thead> 
						 
							<tfoot> 
								<tr> 
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th> 
									<th scope="col" id="name" class="manage-column column-name" style=""><?php _e( 'Name' ); ?></th> 
									<th scope="col" id="description" class="manage-column column-description" style=""><?php _ex( 'Description', 'Taxonomy Description' ); ?></th> 
									<th scope="col" id="slug" class="manage-column column-slug" style=""><?php _e( 'Slug', 'Taxonomy Slug' ); ?></th>  
								</tr> 
							</tfoot> 
						 
							<tbody id="the-list" class="list:tag"> 
						 	<?php foreach ( $bricksets as $brickset ) { ?>
                                <?php brick_system_view::prepend_brickset ( $brickset, $plugin_url ) ?>
						  	<?php } ?>
							</tbody> 
						</table> 
						<br class="clear" /> 
				 
				    </div>
                    <div class="col-wrap">
                      <em><?php _e( 'Be careful with the deletion of a brickset! It will remove the brickset and all its content permanently!', 'thbs' ); ?></em>
                    </div>
				</div><!-- /col-right -->
				<!-- Bricksets -->
 
 				<!-- Add Brickset -->
				<div id="col-left"> 
					<div class="col-wrap"> 
						<div class="form-wrap"> 
							<h3><?php _e( 'Add New Brickset', 'thbs' ); ?></h3> 
							<form id="addBrickset" method="post" action="<?php echo $plugin_url; ?>" class="validate">
								<?php wp_nonce_field( 'bricksetaddnonce', '_wpnonce', false ); ?>
								<div class="form-field form-required"> 
									<label for="set-name"><?php _e( 'Name' ); ?></label> 
									<input name="set-name" id="set-name" type="text" value="" size="40" /> 
								</div> 
								<div class="form-field"> 
									<label for="set-slug"><?php _ex( 'Slug', 'Taxonomy Slug' ); ?></label> 
									<input name="set-slug" id="set-slug" type="text" value="" size="40" /> 
									<p><?php _e( 'The &#8220;slug&#8221; identifies the brickset in the page setup.', 'thbs' ); ?></p> 
								</div> 
								<div class="form-field"> 
									<label for="set-desc"><?php _ex( 'Description', 'Taxonomy Description' ); ?></label> 
									<textarea name="set-desc" id="set-desc" rows="5" cols="40"></textarea> 
									<p><?php _e( 'The description is not a required value. Just give a little preview of the content of the set', 'thbs' ); ?></p> 
								</div>
								 
								<p class="submit"><input type="submit" class="button" name="submit" id="submit" value="<?php _e( 'Add New Brickset', 'thbs' ); ?>" onClick="bricksystem.add_brickset(); return false;" /></p> 
							</form>
						</div>
					</div>
				</div><!-- /col-left -->
				<!-- Add Brickset -->
 
			</div><!-- /col-container -->
		<?php
	}
	
	public static function prepend_brickset ( $brickset, $plugin_url ) {
		?>
            <tr id="tag-<?php echo $brickset->ID; ?>">
                <td scope="row" class="check-column">&nbsp;</td>
                <td class="name column-name">
                    <strong><a class="row-title" href="<?php echo $plugin_url; ?>&action=edit&brickset=<?php echo $brickset->ID; ?>" title="<?php _e( 'Edit', 'thbs' ); ?>&#8220;<?php echo $brickset->post_title; ?>&#8221;"><?php echo $brickset->post_title; ?></a></strong><br />
                    <div class="row-actions">
                        <span class='edit'><a href="<?php echo $plugin_url; ?>&action=edit&brickset=<?php echo $brickset->ID; ?>"><?php _e( 'Edit Brickset', 'thbs' ); ?></a> | </span>
                        <span class='delete'><a class='delete-tag' href='javascript:bricksystem.delete_brickset(<?php echo $brickset->ID; ?>); return false;'><?php _e( 'Delete' ); ?></a></span>
                    </div>
                </td>
                <td class="description column-description"><?php echo $brickset->post_excerpt; ?></td>
                <td class="slug column-slug"><?php echo $brickset->post_name; ?></td>
            </tr>
		<?php
	}
	
	public function edit_brickset_left ( $bricks ) {
		?>
        
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <div id="post-body">
                <div id="post-body-content">
                    <div id="normal-sortables" class="brick-box-sortables ui-sortable">
                        <?php foreach ( $bricks as $brick ) { ?>
                            <?php
                            	$brick->state = get_post_meta( $brick->ID, 'state' );
                            	$brick->state = $brick->state[0];
                            ?>
                            <?php brick_system_view::prepend_brick( $brick ); ?>
                        <?php } ?>

		            </div>
		        </div>
		    </div>
		</div>
        
        <?php
	}
	
	public static function prepend_brick ( $brick, $force_state = null ) {
		?>
                        <div class="postbox main_brick_handler <?php echo $brick->state; ?>"  id="brick-<?php echo $brick->ID; ?>" <?php echo ( 'closed' == $force_state ? 'style="display:none;"' : '' ); ?>>
                            <div class="handlediv" title="<?php _e( 'To switch, click here', 'thbs' ); ?>"><a class="widget-action" href="javascript:bricksystem.slider('brick-<?php echo $brick->ID; ?>','inside');"></a></div>
                            <h3 class="hndle"><span id="brick-title-<?php echo $brick->ID; ?>" class="brick_title"><?php echo $brick->post_title; ?></span> <span class="in-brick_title">Typ: <?php echo $brick->post_mime_type; ?></span></h3>
                            <div class="inside">
                                <form action="" method="post" id="form-<?php echo $brick->ID; ?>" class="brick_edit_form">
                                    <p>
                                    <?php
                                        if ( function_exists( $brick->post_mime_type . '_backend' ) && is_callable( $brick->post_mime_type . '_backend' ) ) {
                                            call_user_func( $brick->post_mime_type . '_backend', $brick );
                                        }
                                   	?>
                                    </p>
                                    
                                    <div class="brick-options" id="inside-brick-<?php echo $brick->ID; ?>" >
                                        <div class="brick-options-wrap">
                                            <div id="submitdiv" class="postbox">
                                                <h3><span><?php _e( 'Publish' ); ?> &amp; <?php _e( 'Save' ); ?> </span></h3>
                                                <div>
                                                    <div class="submitbox" id="submitpost">
                                                        <div id="minor-publishing">
                                                            <div id="minor-publishing-actions">
                                                                <div id="preview-action">
                                                                    <?php
	                                                                    switch ( $brick->post_status ) {
	                                                                        case 'publish':
	                                                                            echo '<a class="preview button" href="javascript:bricksystem.save_brick(' . $brick->ID . ', \'draft\');" tabindex="4" id="draftlink-' . $brick->ID . '">' . __( 'Save as Draft', 'thbs' ) . '</a>';
	                                                                            break;
	                                                                        case 'draft':
	                                                                            echo '<a class="preview button" href="javascript:bricksystem.save_brick(' . $brick->ID . ', \'draft\');" tabindex="4" id="draftlink-' . $brick->ID . '">' . __( 'Save', 'thbs' ) . '</a>';
	                                                                            break;
	                                                                    }
 	                                                                ?>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                            <div id="misc-publishing-actions">
                                                                <div class="indent">
                                                                    <strong><?php _e( 'Status:', 'thbs' ); ?></strong>
                                                                    <span id="status-<?php echo $brick->ID ?>" class="brick-status">
	                                                                    <?php
	                                                                    	switch ( $brick->post_status ) {
	                                                                    		case 'publish':
	                                                                    			echo _e( 'Published' );
	                                                                    			break;
	                                                                    		case 'draft':
	                                                                    			echo _e( 'Draft' );
	                                                                    			break;
	                                                                    	}
	                                                                    ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                
                                                        <div id="major-publishing-actions">
                                                            <div id="delete-action">
                                                                <a class="submitdelete deletion" href="javascript:bricksystem.move_brick_to_trash(<?php echo $brick->ID; ?>, <?php echo $brick->post_parent; ?>);"><?php _e( 'Move to Trash' ); ?></a>
                                                            </div>
                                                            <div id="publishing-action">
                                                                <?php
                                                                    switch ( $brick->post_status ) {
                                                                        case 'publish':
                                                                            echo '<input name="save" type="submit" class="button-primary publishbutton" id="publish-'. $brick->ID .'" tabindex="5" accesskey="p" value="' . __( 'Update', 'thbs' ) . '" onClick="bricksystem.save_brick(' . $brick->ID . ', \'publish\'); return false;" />';
                                                                            break;
                                                                        case 'draft':
                                                                            echo '<input name="save" type="submit" class="button-primary publishbutton" id="publish-'. $brick->ID .'" tabindex="5" accesskey="p" value="' . __( 'Publish', 'thbs' ) . '" onClick="bricksystem.save_brick(' . $brick->ID . ', \'publish\'); return false;" />';
                                                                            break;
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form action="" method="post">
	                                    <div class="brick-options-wrap">
	                                        <div id="submitdiv" class="postbox">
	                                            <h3><span><?php _e( 'Settings' ); ?></span></h3>
	                                            <div>
	                                                <div class="submitbox" id="submitsettings">
	                                                    <div id="minor-publishing">
	                                                        <div class="indent">
	                                                            <label for="name"><?php _e( 'Name' ); ?>:</label>
	                                                            <input type="text" class="brick_name" name="brick_name_<?php echo $brick->ID;?>" id="brick_name_<?php echo $brick->ID;?>" value="<?php echo $brick->post_title; ?>" />
	                                                            <br class="clear" />
	                                                            <label for="name"><?php _e( 'Slug' ); ?>:</label>
	                                                            <input type="text" class="brick_slug" name="brick_slug_<?php echo $brick->ID;?>" id="brick_slug_<?php echo $brick->ID;?>" value="<?php echo $brick->post_name; ?>" />
	                                                            <br class="clear" />
	                                                        </div>
	                                                    </div>
	                                            
	                                                    <div id="major-publishing-actions">
	                                                        <div id="publishing-action">
	                                                            <input onClick="bricksystem.save_brick_options(<?php echo $brick->ID;?>); return false;" name="save" type="submit" class="button-primary" id="save_brick_options" tabindex="5" accesskey="p" value="<?php _e( 'Save' ); ?>" />
	                                                        </div>
	                                                        <div class="clear"></div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="clear"></div>
	                                    </div>
	                                </div>
                                </form>
                            </div>
        <?php
	}
	
	public function edit_brickset_right ( $bricks, $trashed_bricks, $brickset ) {
		?>
        <div class="widget-liquid-right">
            <div id="widgets-right">
            
			    <div class="widgets-holder-wrap" style="margin-top: 0px" id="settings-holder">  <!-- Start Wrapper -->
			        <div class="sidebar-name">
			            <div class="sidebar-name-arrow"><a class="widget-action" href="javascript:bricksystem.slider('settings-holder','widgets-sortables')"></a></div> 
			            <h3><?php _e( 'Settings' ); ?></h3>
			        </div>
			        <div id='bar-right' class='widgets-sortables'>
                    
			            <div class='widget' id='information'> <!-- Start Help Widget -->
			                <div class="widget-top"> 
							    <div class="widget-title-action"> 
							        <a class="widget-action" href="javascript:bricksystem.slider('information', 'widget-inside')"></a> 
							    </div> 
			                    <div class="widget-title"><h4><span class="in-widget-title"><?php _e( 'Help' ); ?></span></h4></div> 
			                </div> 
			 
						    <div class="widget-inside"> 
						        <div class="widget-content"> 
                                    <p><?php _e( 'Here you are able to edit the main settings such as the slug or the name of the brickset.', 'thbs' ) ?></p>
                                    <p><?php _e( 'With the slug you can place the brickset (or a single brick) into your page/template/whatever.', 'thbs' ) ?></p>
                                    <p><?php _e( 'If you change the slug, make sure that you will change the identifier in the pages.', 'thbs' ) ?></p>
                                    <p><?php _e( 'Place a brickset with <em>[brickset "brickset_slug"]</em>. Place a brick width <em>[brick "brick_slug"]</em>.', 'thbs' );?></p>
                                    <p><?php _e( 'Alternatively use the template functions: <em>the_brickset( "brickset_slug" )</em> and <em>the_brick( "brick_slug" )</em>', 'thbs' ); ?></p>
							    </div>
						    </div> 
			            </div> <!-- End Help Widget -->
                        
                        <div class='widget' id='settings'> <!-- Start Settings Widget -->
                            <div class="widget-top"> 
                                <div class="widget-title-action"> 
                                    <a class="widget-action" href="javascript:bricksystem.slider('settings','widget-inside')"></a> 
                                </div> 
                                <div class="widget-title"><h4><span class="in-widget-title"><?php _e( 'Settings' ); ?></span></h4></div> 
                            </div> 
             
                            <div class="widget-inside">
                                <form action="" method="post">
	                                <div class="widget-content"> 
	                                    <p>
	                                        <label for="name"><?php _e( 'Name' ); ?>:</label>
	                                        <input type="text" id="set-name" name="set-name" value="<?php echo $brickset->post_title; ?>" />
	                                        <br class="clear" />
	                                        <label for="name"><?php _e( 'Slug' ); ?>:</label>
	                                        <input type="text" id="set-slug" name="set-slug" value="<?php echo $brickset->post_name; ?>" />
	                                        <br class="clear" />
	                                        <label for="name"><?php _e( 'Description' ); ?>:</label><br />
	                                        <textarea cols="24" id="set-desc" name="set-desc"><?php echo $brickset->post_excerpt; ?></textarea>
	                                    </p>
	                                </div>
	                                <div class="widget-control-actions"> 
	                                    <div class="alignleft">
                                            <input type="hidden" id="set-id" name="set-id" value="<?php echo $_GET['brickset']; ?>" /> 
	                                        <input type="submit" name="savewidget" class="button-primary widget-control-save" value="<?php _e( 'Save' ); ?>" onClick="bricksystem.edit_brickset(); return false;" /> 
	                                    </div> 
	                                    <br class="clear" /> 
	                                </div>
                                </form>
                            </div> 
                        </div> <!-- End Settings Widget -->
                        
			        </div>
                </div> <!-- End Wrapper -->
                
                <div class="widgets-holder-wrap" id="av-brick-holder">  <!-- Start Wrapper -->
                    <div class="sidebar-name">
                        <div class="sidebar-name-arrow"><a class="widget-action" href="javascript:bricksystem.slider('av-brick-holder','widgets-sortables')"></a></div> 
                        <h3><?php _e( 'Available Bricks', 'thbs' ) ?></h3>
                    </div>
                    <div id='bar-right' class='widgets-sortables'>
                    
                        <?php
                        	$i = 1;
                        	foreach ( $bricks as $brick ) {
                        ?>

	                        <div class='widget bricklet' id='brick-<?php echo $i; ?>'> <!-- Start Widget -->
	                            <div class="widget-top"> 
	                                <div class="widget-title-action"> 
	                                    <a class="widget-action" href="javascript:bricksystem.slider('brick-<?php echo $i; ?>','widget-inside')"></a> 
	                                </div> 
	                                <div class="widget-title"><h4><?php echo _e( $brick['name'], 'thbs' ); ?></h4></div> 
	                            </div> 
	             
	                            <div class="widget-inside"> 
	                                <form action="" method="post">
	                                    <div class="widget-content"> 
                                            <p><?php _e( $brick['description'], 'thbs' ); ?></p>
                                            <p><small><?php echo $brick['version']; ?> - <a href="<?php echo $brick['url']; ?>"><?php echo $brick['author']; ?></a> - <?php echo $brick['licence']; ?></small></p>
                                            <input type="hidden" id="set_id" name="set_id" value="<?php echo $_GET['brickset']; ?>" />
                                            <input type="hidden" id="brick_type" name="brick_type" value="<?php echo $brick['slug']; ?>" />
	                                    </div>
	                                </form> 
	                            </div>
                                
                                <?php
                                	$bricklet->ID             = $i;
                                	$bricklet->post_mime_type = $brick['slug'];
                                	$bricklet->post_title     = __( $brick['name'], 'thbs' );
                                	$bricklet->post_name      = $brick['slug'];
                                	$bricklet->post_status    = 'draft';
                                	brick_system_view::prepend_brick( $bricklet, 'closed' );
                                ?>
                                
                                
	                        </div> <!-- End Widget -->
                        
                        <?php
                        	++$i;
						}
                        ?>
                        
                    </div>
                </div> <!-- End Wrapper -->
                
                <?php if ( count ( $trashed_bricks ) >= 1 ) : ?>
                    <?php brick_system_view::show_brick_trash( $trashed_bricks ); ?>
                <?php endif; ?>
                
            </div>
        </div>
    	<br class="clear" />
		<?php
	}
	
	public static function show_brick_trash ( $bricks, $set_id = null ) {
		if ( !$set_id ) {
			$set_id = $_GET['brickset'];
		}
		?>
                <div class="widgets-holder-wrap" id="trash-brick-holder">  <!-- Start Wrapper -->
                    <div class="sidebar-name">
                        <div class="sidebar-name-arrow"><a class="widget-action" href="javascript:bricksystem.slider('trash-brick-holder','widgets-sortables')"></a></div> 
                        <h3><?php _e( 'Trash' ) ?></h3>
                    </div>
                    <div id='trash-right' class='widgets-sortables'>
                        <div class="alignright emptytrash">
                            <input type="submit" name="emptytrash" class="button-primary widget-control-save" value="<?php _e( 'Empty Trash' ); ?>" onClick="bricksystem.empty_brickset_trash(<?php echo $set_id; ?>); return false;" /> 
                        </div> 
                        <?php
                            foreach ( $bricks as $brick ) {
                            	brick_system_view::append_trashed_brick( $brick );
                            }
                        ?>
                    </div>
                </div> <!-- End Wrapper -->
        <?php
	}
	
	public static function append_trashed_brick ( $brick ) {
		?>
                            <div class='widget trashed' id='trash-<?php echo $brick->ID; ?>'> <!-- Start Widget -->
                                <div class="widget-top"> 
                                    <div class="widget-title-action"> 
                                        <a class="widget-action" href="javascript:bricksystem.slider('trash-<?php echo $brick->ID; ?>','widget-inside')"></a> 
                                    </div> 
                                    <div class="widget-title"><h4><?php echo $brick->post_title ?></h4></div> 
                                </div> 
                 
                                <div class="widget-inside"> 
                                    <form action="" method="post">
                                        <div class="widget-control-actions"> 
                                            <div class="alignleft"> 
                                                <input type="submit" name="deletewidget" class="button-secondary widget-control-save" value="<?php _e( 'Delete' ); ?>" onClick="bricksystem.delete_brick(<?php echo $brick->ID; ?>); return false;" /> 
                                            </div>
                                            <div class="alignright"> 
                                                <input type="submit" name="restorewidget" class="button-primary widget-control-save" value="<?php _e( 'Restore' ); ?>" onClick="bricksystem.restore_brick(<?php echo $brick->ID; ?>); return false;" /> 
                                            </div> 
                                            <br class="clear" /> 
                                        </div>
                                    </form> 
                                </div>
                                
                                
                            </div> <!-- End Widget -->
        <?php
	}
}