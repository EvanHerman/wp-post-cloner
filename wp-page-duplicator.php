<?php
/*
Plugin Name: WP Page Cloner
Plugin URI:  
Description: This will add a custom button to duplicate pages and posts
Version:     0.1
Author:      Evan Herman, Ben Rothman
Author URI:  
 */

class Page_Duplicator {
	
	public function __construct() {
		// Add custom clone post button
		add_filter( 'page_row_actions', array( $this, 'add_clone_post_button' ), 10, 2 );
		// Intercept the action
		add_action( 'init', array( $this, 'clone_dat_page' ) );
		// Display clone notices
		add_action( 'admin_notices', array( $this, 'clone_dat_page_admin_notice' ) );
	}
	
	/*
	*	Create a custom 'Clone Post' button on the Page screen in the row_actions span
	*	@since 0.1
	*/
	public function add_clone_post_button( $actions, $post ) {
		$actions['clone_post'] = '<a href="'. add_query_arg( 
			array( 
				'do_action' => 'clone_post',
				'nonce' => wp_create_nonce( 'clone_post-' . (int) $post->ID ), 
				'post_id' => (int) $post->ID 
			), 
			esc_url( admin_url( 'edit.php?post_type=page' ) ) 
		) . '" >Clone Page</a>';
		return $actions;		
	}
	
	/*
	*	Clone the actual page
	*	@since 0.1
	*/
	public function clone_dat_page() {
		if( isset( $_GET['do_action'] ) && $_GET['do_action'] == 'clone_post' && isset( $_GET['nonce'] ) ) {
			wp_verify_nonce( $_GET['nonce'], 'clone_post-' . $_REQUEST['post_id'] );
			$page_id = (int) $_GET['post_id'];
			$page_object = get_post( $page_id );
			if( $page_object ) {
				// wp_die( print_r( $page_object ) );
				
				$new_page_title = $page_object->post_title . ' - Clone';
				$new_page_author = $page_object->post_author;
				$new_page_content = $page_object->post_content;
				$new_page_image_id = get_post_thumbnail_id( $page_id );
				
				// Create post object
				$my_post = array(
				  'post_title'    => $new_page_title,
				  'post_content'  => $new_page_content,
				  'post_type' => 'page',
				  'post_status'   => 'draft',
				  'post_author'   => $new_page_author,
				);

				// Insert the post into the database
				$new_post = wp_insert_post( $my_post );
				if( $new_post ) {
					if( $new_page_image_id ) {
						set_post_thumbnail( $new_post, $new_page_image_id );
					}
					wp_redirect( esc_url_raw( admin_url( 'edit.php?post_type=page&post_duplicated=true&cloned_post=' . (int) $new_post ) ) );
					exit();
				}
			}
		}
	}
	
	/*
	*	Display Clone Notices
	*	@since 0.1
	*/
	public function clone_dat_page_admin_notice() {
		if( isset( $_GET['post_duplicated'] ) && $_GET['post_duplicated'] == 'true' ) {
			$page_id = (int) $_GET['cloned_post'];
			$page_data = get_post( $page_id );
			?>
			<div class="updated">
				<p><?php echo $page_data->post_title; ?> Sucessfully Cloned! <a href="<?php echo esc_url_raw( admin_url( 'post.php?post=' . $page_id . '&action=edit' ) ); ?>">edit post</a></p>
			</div>
			<?php
		}
	}

	
}
new Page_Duplicator;