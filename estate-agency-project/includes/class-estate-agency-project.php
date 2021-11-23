<?php

/**
 * Core plugin class
 *
 * @since      1.0.0
 */

class Estate_Agency_Project {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The different value for nature field
	 *
	 * @since    1.0.0
	 * @var      array    $NATURE    The different value for nature field
	 */
	const NATURE=["Non spécifié", "Maison","Appartement","Terrain","Bureau","Commerce","Garage"];

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_EAP_VERSION' ) ) {
			$this->version = PLUGIN_EAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'Estate Agency Project';
	}

	/**
	 * Run 
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->load_hook();
		$this->add_filter();
	}

	/**
	 * Retrieve the name of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * load all the plugin hook used 
	 *
	 * @since    1.0.0
	 */
	public function load_hook() {
		// add custom post type
		add_action( 'init', [$this, 'eap_create_posttype'] );

		// add css
		add_action( 'init', [$this, 'eap_add_css'] );

		// custom field for previous post type
		add_action( 'add_meta_boxes', [$this, 'eap_set_meta_box'] );
		add_action( 'save_post', [$this, 'eap_save_custom_field'] );

		// add page to previous custom type
		add_action( 'admin_menu', [$this, 'eap_link_menu'] );

		// change defaut post type
		add_action( 'pre_get_posts', [$this, 'eap_update_default_post_type'] );
	}

	/**
	 * load all the plugin filter used 
	 *
	 * @since    1.0.0
	 */
	public function add_filter() {
		// Filter the template for the custom post type real estate
		add_filter( 'single_template', [$this, 'eap_single_real_estate_template'] );
		add_filter( 'archive_template', [$this, 'eap_archive_real_estate_template'] );
	}

	/**
	 * Create custom post type for real estate
	 *
	 * @since     1.0.0
	 */
	function eap_create_posttype() {

		// Set UI labels for Custom Post Type
	    $labels = [
	        'name'                => 'Biens immobiliers',
	        'singular_name'       => 'real_estate',
	        'menu_name'           => 'Biens immobiliers',
	        'all_items'           => 'Tous les biens',
	        'view_item'           => 'Voir les biens',
	        'add_new_item'        => 'Ajouter un bien',
	        'add_new'             => 'Ajouter',
	        'edit_item'           => 'Modifier le bien',
	        'update_item'         => 'Mettre à jour un bien',
	        'search_items'        => 'Rechercher un bien',
	    ];
	     
		// Set other options for Custom Post Type
	    $args = [
	        'label'               => 'real_estate',
	        'description'         => 'real estate project',
	        'labels'              => $labels,
	        'show_in_rest' 		  => false,
			'hierarchical'        => false,
			'public'              => true,
			'has_archive'         => true,
	        'menu_position'       => 5,
	        'menu_icon'      	  => 'dashicons-admin-home',
        	'supports'            => ['title', 'thumbnail'],
	    ];
	     
	    // Registering your Custom Post Type
	    register_post_type( 'real_estate', $args );
	}

	/**
	 * Add css, for the front-end 
	 *
	 * @since     1.0.0
	 */
	public function eap_add_css() {
		wp_register_style( 'css', plugins_url( '/css/style.css',__DIR__ ) );
	    wp_enqueue_style( 'css' );
	}

	/**
	 * Add custom meta box for custom type 'real_estate'
	 *
	 * @since     1.0.0
	 */
	function eap_set_meta_box() {
		//meta box for custom information
	    add_meta_box(
	        'eap_information',
	        'Informations',
	        [$this, 'eap_template_information'],
	        'real_estate'
	    );
	}

	/**
	 * Template for price display in custom type 'real_estate'
	 *
	 * @since     1.0.0
	 * @param 	  object  	$post
	 */
	function eap_template_information( $post ) {
		$eap_price=get_post_meta( $post->ID, 'eap_price', true );
		$eap_area=get_post_meta( $post->ID, 'eap_area', true );
		$eap_city=get_post_meta( $post->ID, 'eap_city', true );
		$eap_nature=get_post_meta( $post->ID, 'eap_nature', true );
		?>
		<table>
			<tr>
				<td><label>Prix (€) : </label></td>
				<td><input type="text" id="eap_price" name="eap_price" value="<?php echo esc_attr(  $eap_price ); ?>" /></td>
			</tr>
			<tr>
				<td><label>Surface (m²) : </label></td>
				<td><input type="text" id="eap_area" name="eap_area" value="<?php echo esc_attr(  $eap_area ); ?>" /></td>
			</tr>
			<tr>
				<td><label>Ville : </label></td>
				<td><input type="text" id="eap_city" name="eap_city" value="<?php echo esc_attr(  $eap_city ); ?>" /></td>
			</tr>
			<tr>
				<td><label>Nature du bien : </label></td>
				<td>
					<select id="eap_nature" name="eap_nature" style="width: 100%;" >
						<?php 
						//build dynamic option based on const nature
						foreach ( Estate_Agency_Project::NATURE as $nature ) {
							$selected="";
							if ( $eap_nature == $nature )
								$selected="selected";
							echo "<option value='".$nature."' ".$selected." >".$nature."</option>";
						}
						?>
				    </select>
				</td>
			</tr>
		</table>
	    <?php 
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @since     1.0.0
	 * @param 	  int 		$post_id
	 */
	function eap_save_custom_field( $post_id ) {
	    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }

	    // Check the user's permissions.
	    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
	        if ( ! current_user_can( 'edit_page', $post_id ) ) {
	            return;
	        }
	    }
	    else {
	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	        }
	    }

	    /* OK, it's safe to save the data now. */

	    //save each custom post meta
		// Update the meta field in the database.
	    if ( isset( $_POST['eap_price'] ) )
		    update_post_meta( $post_id, 'eap_price', sanitize_text_field( $_POST['eap_price'] ) );

	    if ( isset( $_POST['eap_area'] ) )
		    update_post_meta( $post_id, 'eap_area', sanitize_text_field( $_POST['eap_area'] ) );

	    if ( isset( $_POST['eap_city'] ) )
		    update_post_meta( $post_id, 'eap_city', sanitize_text_field( $_POST['eap_city'] ) );

	    if ( isset( $_POST['eap_nature'] ) ) {
	    	//check if value is in const NATURE
	    	if ( in_array( $_POST['eap_nature'], Estate_Agency_Project::NATURE )){
		    	// Update the meta field in the database.
		    	update_post_meta( $post_id, 'eap_nature', sanitize_text_field( $_POST['eap_nature'] ) );
	    	}
	    }

	}

	/**
	 * add page to custom post type 'real_estate'
	 *
	 * @since     1.0.0
	 */
	function eap_link_menu() {
    	add_submenu_page(
	        'edit.php?post_type=real_estate',
	        'Importer',
	        'Importer',
	        'manage_options',
	        'import',
	        [$this, 'eap_template_import_page']
	    );
	}
	 
	/**
	 * template for import page of custom type 'real_estate'
	 *
	 * @since     1.0.0
	 */
	function eap_template_import_page() {
		?>
		<h1>Import biens immobiliers</h1>
		<form action="#" method="post" enctype="multipart/form-data">
			<label>Fichier JSON:</label>
			<input type="file" name="jsonFile" id="jsonFile">
			<input type="submit" name="submit" value="Importer">
		</form>
		<?php 

		//bind function for post traitement
	    if ( isset( $_POST['submit'] ) )
	    	$this->eap_post_file();
	}

	/**
	 * traitement import json file
	 *
	 * @since     1.0.0
	 */
	function eap_post_file() {

		//note : this is really minimalist test. 
		if ( $_FILES['jsonFile']['type'] != "application/json" )
            return;

        //note : actually if the server cant read the file before timeout, there will be a problem. To avoid this a solution is to fragment the file and do it in several ajax calls. Actually for 2mo JSON file the server can respond.
    	$json = json_decode( file_get_contents( $_FILES['jsonFile']['tmp_name'] ) );

    	//check if the format of the json is ok
    	if ( ! isset( $json->data ) )
            return;

    	echo "<h2>Traitement de " . count( $json->data ) . " biens immobiliers</h2>";

    	?>
    	<table><th>Titre</th><th>Prix</th><th>Surface</th><th>Ville</th><th>Nature du bien</th>
	    	<?php
	    	$i = 0;
	    	foreach ( $json->data as $index => $j) {
	    		$titre = "Bien sans titre Nᵒ" . $index;
	    		if ( isset( $j->info->titre ) && $j->info->titre != "" )
	    			$titre=$j->info->titre;

	    		$prix = "Non spécifié";
	    		if ( isset( $j->prix->budget ) && $j->prix->budget != "" )
	    			$prix=$j->prix->budget;

	    		$surface = "Non spécifié";
	    		if ( isset( $j->info->surface ) && $j->info->surface != "" )
	    			$surface=$j->info->surface;

	    		$ville = "Non spécifié";
	    		if ( isset( $j->localisation->ville ) && $j->localisation->ville != "" )
	    			$ville=$j->localisation->ville;

	    		$nature = "Non spécifié";
	    		if ( isset( $j->info->nature ) && $j->info->nature != "" )
	    			$nature=$j->info->nature;

	    		$img = null;
	    		$index = 1;
	    		if ( isset( $j->photos->$index->url ) && $j->photos->$index->url != "" )
	    			$img = $j->photos->$index->url;
	    		?>
	    		<tr>
	    			<td><?php echo $titre; ?></td>
	    			<td><?php echo $prix; ?></td>
	    			<td><?php echo $surface; ?></td>
	    			<td><?php echo $ville; ?></td>
	    			<td><?php echo $nature; ?></td>
	    		</tr>
	    		<?php 
	    		//save 
	    		$this->setRealEstate( $titre, $prix, $surface, $ville, $nature, $img );
	    	}
	    	?>
    	</table>
    	<?php 
	}

	/**
	 * Set real estate in base
	 * french note : plusieurs point ici : 
	 * - Je fais le choix d'enregistrer directement les valeurs en base. Je vais insérer des lignes dans les tables posts et postsmeta. Il faut être consient ici qu'on va fortement soliciter la base, 1 requête insert pour la table posts et 4 requêtes insert pour la table postmeta = 5x174(nb de bien dans le json actuel) = 870 requête insert. Cela devrait passer mais pour un plus grand volume il faudrait revoir ce traitement. 
	 * - Il n'y a pas pour le moment de test d'existance pour vérifier si un bien existe déjà en base.
	 * - Il n'y a pas pour le moment de proection contre l'injection sql
	 *
	 * @since     1.0.0
	 */
	public function setRealEstate( $titre, $prix, $surface, $ville, $nature , $img ) {
		global $wpdb;

		$post_name = str_replace( " ", "-", strtolower( $titre ) );
		//step 1 insert line in posts table
		$res = $wpdb->insert( $wpdb->prefix . 'posts', [
			'post_type' 				=> 'real_estate',
   			'post_status' 				=> 'publish',
   			'comment_status' 			=> 'closed',   
   			'ping_status' 				=> 'closed',      
		    'post_author' 				=> get_current_user_id(),
		    'post_content' 				=> '',
		    'post_title' 				=> $titre,
		    'post_name' 				=> urlencode( $post_name ),
		    'post_excerpt' 				=> '',
		    'to_ping' 					=> '',
		    'pinged' 					=> '',
		    'post_content_filtered' 	=> '',
		    'post_date' 				=> date("Y-m-d H:i:s"),
		    'post_date_gmt' 			=> date("Y-m-d H:i:s"),
		    'post_modified' 			=> date("Y-m-d H:i:s"),
		    'post_modified_gmt' 		=> date("Y-m-d H:i:s"),
		]);

	    //step 2 insert multi line in postmeta
		//if $res that meen we can insert value
		$post_id = null;
		if ( $res ) {
			$post_id = $wpdb->insert_id;
			// insert post meta
			add_post_meta( $post_id, 'eap_price', $prix);
			add_post_meta( $post_id, 'eap_area', $surface);
			add_post_meta( $post_id, 'eap_city', $ville);
			add_post_meta( $post_id, 'eap_nature', $nature);
		}

		//step 3 Thumbnail
		if ( $res ) {
			$attach_id = $this->crb_insert_attachment_from_url( $img, $post_id );
			set_post_thumbnail( $post_id, $attach_id );
		}

		//Display query for debuging
		/*if ( $wpdb->last_error !== '' ) { 
        	$query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );
        	print "<code>$query</code>";

		}*/
	}

	/**
	 * I did not do this function 
	 * upload a image from a url to wordpress media system and return id
	 *
	 * @source 	  https://gist.github.com/m1r0/f22d5237ee93bcccb0d9
	 * @since     1.0.0
	 * @param  	  String 	$url
	 * @param     Int    	$parent_post_id
	 * @return    Int    	Attachment ID
	 */
	function crb_insert_attachment_from_url($url, $parent_post_id = null) {
		if ( !class_exists( 'WP_Http' ) )
			include_once( ABSPATH . WPINC . '/class-http.php' );

		$http = new WP_Http();
		$response = $http->request( $url );
		if ( $response['response']['code'] != 200 )
			return false;

		$basename=str_replace("[", "", $url);
		$basename=str_replace("]", "", $url);
		$upload = wp_upload_bits( basename($basename), null, $response['body'] );
		if ( !empty( $upload['error'] ) )
			return false;

		$file_path = $upload['file'];
		$file_name = basename( $file_path );
		$file_type = wp_check_filetype( $file_name, null );
		$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
		$wp_upload_dir = wp_upload_dir();

		$post_info = [
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		];

		// Create the attachment
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id,  $attach_data );

		return $attach_id;
	}

	/**
	 * change default post type
	 *
	 * @param 	  object  	$query
	 * @since     1.0.0
	 */
	function eap_update_default_post_type( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			//set default post type
	    	if ( $query->is_home )
	    		$query->set('post_type', ['post', 'real_estate']);
	    	if ( $query->is_search )
		    	$query->set('post_type', ['post', 'real_estate']);
	  	}
	}

	/**
	 * set the single template for the custom post type real estate
	 *
	 * @param 	  string  	$template
	 * @since     1.0.0
	 * @return    string 	$template
	 */
	function eap_single_real_estate_template( $template ) {
	    global $post;

	    // Checks for single template by post type 
	    if ( $post->post_type == 'real_estate' ) {
	        if ( file_exists( __DIR__ . "/../templates/single-real-estate.php" ) )
	            $template = __DIR__ . "/../templates/single-real-estate.php";
	    }

	    return $template;
	}

	/**
	 * set the archive template for the custom post type real estate
	 *
	 * @since     1.0.0
	 * @param 	  string  	$template
	 * @return    string 	$template
	 */
	function eap_archive_real_estate_template( $template ) {
	    global $post;

	     // Checks for archive template by query name 
	    if ( get_queried_object()->name == 'real_estate' ) {
	        if ( file_exists( __DIR__ . "/../templates/archive-real-estate.php" ) )
	            $template = __DIR__ . "/../templates/archive-real-estate.php";
	    }

	    return $template;
	}
}