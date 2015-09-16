<?php 
/*
*	General WP Page Cloner options page
*	@since 0.1
*/

// Check that the user is allowed to update options
if (! current_user_can('manage_options')) {
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-page-cloner' ) );
}

?>
<div class="wrap">
	<h1><?php _e( 'WP Page Cloner', 'wp-page-cloner' ); ?></h1>	
	<form method="POST" action="options.php">
		<?php 
			settings_fields( 'wp-page-cloner-settings' );	//pass slug name of page, also referred to in Settings API as option group name
			do_settings_sections( 'wp-page-cloner-settings' ); 	//pass slug name of page
			submit_button();
		?>		
	</form>
	
</div>



