<?php
/*
Plugin Name: WP Post Cloner
Plugin URI:  
Description: WP Post cloner allows you to easily make complete duplicates of any post on your site. That includes posts, pages and custom post types.
Version:     0.1
Author:      Evan Herman, Ben Rothman
Author URI:  
 */

class Page_Duplicator {
	
	public function __construct() {
		$cloneable_posts = get_option( 'cloneable_post_types', array(
			'post',
			'page'
		) );
		if( ! empty( $cloneable_posts ) ) {
			foreach( $cloneable_posts as $cloneable ) {
				// Add custom clone post button
				add_filter( $cloneable . '_row_actions', array( $this, 'add_clone_post_button' ), 10, 2 );
			}
		}
		// Intercept the action
		add_action( 'init', array( $this, 'clone_page' ) );
		// Display clone notices
		add_action( 'admin_notices', array( $this, 'clone_page_admin_notice' ) );
		// Add custom settings page
		add_action( 'admin_menu', array( $this, 'create_wp_page_cloner_settings' ) );
		// Register our settings
		add_action( 'admin_init', array( $this, 'wp_page_cloner_settings_api_init' ) );
		// Enqueue scripts/styles where needed
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_page_cloner_enqueue_scripts_and_styles' ) );
	}
	
	
	/* BEGIN SETTINGS PAGE */
	/*****************************/
	
	/*
	*	Register and display our settings page
	*	@since 0.1
	*/
	public function create_wp_page_cloner_settings() {
		add_submenu_page( 
			  'options-general.php',   //or 'options.php' 
			  __( 'WP Post Cloner', 'wp-post-cloner' ), 
			__( 'WP Post Cloner', 'wp-post-cloner' ), 
			'manage_options', 
			'wp-post-cloner-settings', 
			array( $this, 'wp_page_cloner_settings_page' )
		);
	}	
	
	/*
	*	Add our settings sections / settings
	*	@since 0.1
	*/
	public function wp_page_cloner_settings_api_init() {
	
		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting(
			'wp-post-cloner-settings',
			'cloneable_post_types'
		);
	
		// Add the section to reading settings so we can add our		
		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'wp-post-cloner-settings',         // ID used to identify this section and with which to register options
			__( 'Post Type Options', 'wp-post-cloner' ),                // Title to be displayed on the administration page
			array( $this, 'wp_page_cloner_setting_section_callback_function' ), // Callback used to render the description of the section
			'wp-post-cloner-settings'                           // Page on which to add this section of options
		);
		
		// Add the field with the names and function to use for our new
		// settings, put it in our new section
		add_settings_field( 
			'cloneable_post_types',                      // ID used to identify the field throughout the theme
			__( 'Post Types', 'wp-post-cloner' ),                           // The label to the left of the option interface element
			array( $this, 'wp_page_cloner_cloneable_post_types_callback_function' ),   // The name of the function responsible for rendering the option interface
			'wp-post-cloner-settings',                       // The page on which this option will be displayed
			'wp-post-cloner-settings',         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback. In this case, just a description.
				__( 'Select which post types should be clone-able. By default, posts and pages are clone-able.', 'wp-post-cloner' )
			)
		);
				
	} // eg_settings_api_init()
  
	/*
	*	Include our page cloner settings page
	*	@since 0.1
	*/
	public function wp_page_cloner_settings_page() {
		include_once( plugin_dir_path( __FILE__ ) . 'includes/wp-post-duplicator-settings.php' );
	}
	
	/*
	*	Setup some descriptive text for this section
	*	@since 0.1
	*/
	public function wp_page_cloner_setting_section_callback_function() {
		echo '<p>' . __( 'Select which post types should be clone-able.', 'wp-post-cloner' ) . '</p>';
	 }
	 
	/*
	*	Setup our custom post type selection field
	*	@since 0.1
	*/
	public function wp_page_cloner_cloneable_post_types_callback_function( $args ) {
		$previously_saved_post_types = get_option( 'cloneable_post_types', array(
			'post',
			'page'
		) );
		$post_types = get_post_types( '', 'names' );
		unset( $post_types[ 'attachment' ] );
		unset( $post_types[ 'nav_menu_item'] );
		unset( $post_types[ 'revision' ] );
		?>
			<select id="select_cloneable_post_types" name="cloneable_post_types[]" multiple>
				<?php
					foreach ( $post_types as $post_type ) {
						$post_type_labels = get_post_type_object( $post_type );
						$post_type_name = ( isset( $post_type_labels->labels->singular_name ) ) ? $post_type_labels->labels->singular_name : $post_type;
						$selected = ( in_array( $post_type, $previously_saved_post_types ) ) ? 'selected="selected"' : '';
						echo '<option value="' . $post_type . '" ' . $selected . '>' . ucwords( $post_type_name ) . '</option>';
					}
				?>
			</select>
			<p class="description"><?php echo $args[0]; ?></p>
		<?php
	}
	
	
	/* END SETTINGS PAGE */
	/***************************/
	
	
	
	/*
	*	Create a custom 'Clone Post' button on the Page screen in the row_actions span
	*	@since 0.1
	*/
	public function add_clone_post_button( $actions, $post ) {
		$cloneable_posts = get_option( 'cloneable_post_types', array(
			'post',
			'page'
		) );
		if( ! empty( $cloneable_posts ) ) {
			foreach( $cloneable_posts as $cloneable ) {
				/* Confirm any custom post types are assigned on the settings page */
				if( $cloneable == 'post' ) {
					if( ! in_array( $post->post_type, $cloneable_posts ) ) {
						return $actions;
					}
				}
				$post_type_labels = get_post_type_object( $post->post_type );
				$actions['clone_post'] = '<a href="'. add_query_arg( 
					array( 
						'do_action' => 'clone_post',
						'nonce' => wp_create_nonce( 'clone_post-' . (int) $post->ID ), 
						'post_id' => (int) $post->ID 
					), 
					esc_url( admin_url( 'edit.php?post_type=page' ) ) 
				) . '" >' . sprintf( __( 'Clone %s', 'yikes-inc-easy-mailchimp-extender' ), $post_type_labels->labels->singular_name ) . '</a>';
			}
		}
		return $actions;		
	}
	
	/*
	*	Clone the actual page
	*	@since 0.1
	*/
	public function clone_page() {
		if( isset( $_GET['do_action'] ) && $_GET['do_action'] == 'clone_post' && isset( $_GET['nonce'] ) ) {
			wp_verify_nonce( $_GET['nonce'], 'clone_post-' . $_REQUEST['post_id'] );
			$page_id = (int) $_GET['post_id'];
			$page_object = get_post( $page_id );
			$taxonomies = get_object_taxonomies( $page_object->post_type );
			$post_meta_data = get_post_meta( $page_id );
			
			if( $page_object ) {
				
				$new_page_title = $page_object->post_title . ' - Clone';
				$new_page_author = $page_object->post_author;
				$new_page_content = $page_object->post_content;
				$new_page_image_id = get_post_thumbnail_id( $page_id );
				
				// Create post object
				$my_post = array(
				  'post_title'    => $new_page_title,
				  'post_content'  => $new_page_content,
				  'post_type' => $page_object->post_type,
				  'post_status'   => 'draft',
				  'post_author'   => $new_page_author,
				);
				
				// Insert the post into the database
				$new_post = wp_insert_post( $my_post );
				if( $new_post ) {
					// Loop over returned taxonomies, and re-assign them to the new post_type
					if( $taxonomies ) {
						foreach( $taxonomies as $taxonomy ) {
							$terms = wp_get_post_terms( $page_id, $taxonomy );
							if( $terms ) {
								$assigned_terms = array();
								foreach( $terms as $assigned_term ) {
									$assigned_terms[] = $assigned_term->term_id;
								}
								wp_set_object_terms( $new_post, $assigned_terms, $taxonomy, false );
							}
						}
					}
					// Loop over returned metadata, and re-assign them to the new post_type
					if( $post_meta_data ) {
						foreach( $post_meta_data as $meta_data => $value ) {
							if( is_array( $value ) ) {
								foreach( $value as $meta_value ) {
									update_post_meta( $new_post, $meta_data, $meta_value );
								}
							} else {
								update_post_meta( $new_post, $meta_data, $value );
							}
						}
					}
					// re-assign the featured image
					if( $new_page_image_id ) {
						set_post_thumbnail( $new_post, $new_page_image_id );
					}
					wp_redirect( esc_url_raw( admin_url( 'edit.php?post_type=' . $page_object->post_type . '&post_duplicated=true&cloned_post=' . (int) $new_post ) ) );
					exit();
				}
			}
		}
	}
	
	/*
	*	Display Clone Notices
	*	@since 0.1
	*/
	public function clone_page_admin_notice() {
		if( isset( $_GET['post_duplicated'] ) && $_GET['post_duplicated'] == 'true' ) {
			$page_id = (int) $_GET['cloned_post'];
			$page_data = get_post( $page_id );
			?>
			<div class="updated">
				<p><?php echo str_replace( ' - Clone', '', $page_data->post_title ); ?> <?php _e( 'Sucessfully Cloned', 'wp-post-cloner' ); ?> &#187; <a href="<?php echo esc_url_raw( admin_url( 'post.php?post=' . $page_id . '&action=edit' ) ); ?>">edit post</a></p>
			</div>
			<?php
		}
	}

	/*
	*	Enqueue the page cloner scripts/styles where needed
	*	@since 0.1
	*/
	public function wp_page_cloner_enqueue_scripts_and_styles( $hook ) {
		// on our settings page, let's enqueue select2
		if( $hook == 'settings_page_wp-post-cloner-settings' ) {
			wp_enqueue_script( 'select2.min.js', plugin_dir_url( __FILE__ ) . 'includes/js/select2.min.js', array( 'jquery' ), 'all', true );
			wp_enqueue_script( 'select2-init.js', plugin_dir_url( __FILE__ ) . 'includes/js/select2-init.js', array( 'select2.min.js' ), 'all', true );
			wp_enqueue_style( 'select2.min.css', plugin_dir_url( __FILE__ ) . 'includes/css/select2.css' );
		}
	}
	
}
new Page_Duplicator;