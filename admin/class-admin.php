<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://baonguyenyam.github.io/cv
 * @since      1.0.0
 *
 * @package    LIFT_Chat
 * @subpackage LIFT_Chat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LIFT_Chat
 * @subpackage LIFT_Chat/admin
 * @author     Nguyen Pham <baonguyenyam@gmail.com>
 */

class LIFT_Chat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $liftChat    The ID of this plugin.
	 */
	private $liftChat;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $liftChat       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $liftChat ) {

		$this->liftChat = $liftChat;
		add_action( 'carbon_fields_register_fields', array( $this, '___app_option_attach_theme_options' ));
		add_action( 'after_setup_theme', array( $this, '__settingUP' ));

		add_action('admin_menu', array( $this, '___addPluginAdminMenu' ));   
		add_action( 'admin_post_submit_data', array( $this, '__submitData') );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LIFT_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LIFT_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->liftChat['domain'], plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->liftChat['version'], 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LIFT_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LIFT_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->liftChat['domain'], plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->liftChat['version'], false );

	}

	public function __submitData() {
		global $table_prefix, $wpdb;
		$tblGroup = $table_prefix . LIFT_CHAT_PREFIX . '_suggest_group';
		$tblSuggest = $table_prefix . LIFT_CHAT_PREFIX . '_suggest';

		$id = stripslashes_deep($_POST['id']);
		$type = stripslashes_deep($_POST['type']);
		$posttype = stripslashes_deep($_POST['posttype']);
		$inputValue = stripslashes_deep($_POST['groupName']);
		$groupTarget = stripslashes_deep($_POST['groupTarget']);
		$idTarget = stripslashes_deep($_POST['idTarget']);

		if($posttype === 'suggest') {
			if(isset($type) && $type != '' && $type != null) {
				if($type === 'edit') {
					$wpdb->update(
						$tblSuggest,
						array(
							'suggest_content'=> $inputValue,
							'group_id' => $groupTarget,
							'target_id' => $idTarget,
						),
						array('suggest_id'=>$id),
					);
				}
				if($type === 'delete') {
					$wpdb->delete(
						$tblSuggest,
						array(
							'suggest_id'=> $id
						),
						array('%d'),
					);
				}
			} else {
				$wpdb->insert(
					$tblSuggest,
					array( 
						'suggest_content' => $inputValue,
						'group_id' => $groupTarget,
						'target_id' => $idTarget,
					),
					array( '%s' ),
				);
			}
		}

		if($posttype === 'screen') {
			if(isset($type) && $type != '' && $type != null) {
				if($type === 'edit') {
					$wpdb->update(
						$tblGroup,
						array(
							'group_content'=> $inputValue
						),
						array('group_id'=>$id),
					);
				}
				if($type === 'delete') {
					$wpdb->delete(
						$tblGroup,
						array(
							'group_id'=> $id
						),
						array('%d'),
					);
				}
			} else {
				$wpdb->insert(
					$tblGroup,
					array( 
						'group_content' => $inputValue
					),
					array( '%s' ),
				);
			}
		}
	
		wp_redirect('admin.php?page=lift-chat');
		// wp_redirect($_SERVER["HTTP_REFERER"]);
	}


	public function ___addPluginAdminMenu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  $this->liftChat['nicename'], $this->liftChat['nicename']. '', 'administrator', $this->liftChat['domain'], array( $this, '___displayPluginAdminDashboard' ), 'dashicons-admin-comments', 30 );
		
		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page( null, 'Add New Screen', 'Add New Screen', 'administrator', $this->liftChat['domain'].'-screen', array( $this, '___displayPluginAdminAddNewScreen' ));
		add_submenu_page( null, 'Add New Suggest', 'Add New Suggest', 'administrator', $this->liftChat['domain'].'-suggest', array( $this, '___displayPluginAdminAddNewSuggest' ));

	}
	public function ___displayPluginAdminDashboard() {
		require_once 'partials/admin-display.php';
	}
	public function ___displayPluginAdminAddNewScreen() {
		require_once 'partials/screen.php';
	}
	public function ___displayPluginAdminAddNewSuggest() {
		require_once 'partials/suggest.php';
	}

	public function ___app_option_attach_theme_options() {
		$basic_options_container =  Container::make( 'theme_options', __(  $this->liftChat['nicename']. ' Settings' ) )
		->set_page_parent(  $this->liftChat['domain'] )
			// ->set_page_menu_title( 'App Settings' )
			// ->set_page_menu_position(2)
			// ->set_icon( 'dashicons-admin-generic' )
			->add_tab( __( 'Settings' ), self::__chatApp() )
			->add_tab( __( base64_decode('Q29weXJpZ2h0') ), self::__copyright() )
			;
	}
	
	public function __settingUP() {
		require_once plugin_dir_path( __DIR__  ) .'vendor/autoload.php';
		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function __chatApp() {
		$data = array();
		$data = array(
			Field::make( 'text', '__lift_chat_title', __( 'Title' ) )
			->set_default_value('Chat with us!')
			->set_classes( 'lift-cabon-width-class' )
			->set_width(100),
			Field::make( 'image', '__lift_chat_logo', __( 'Logo' ) )
			->set_value_type( 'url' )
            ->set_visible_in_rest_api( $visible = true )
			->set_width(100),
			Field::make( 'color', '__lift_chat_style', 'Style color' )
			->set_alpha_enabled( true )
			->set_width(100),	
			Field::make( 'text', '__lift_chat_size', __( 'Icon Size' ) )
			->set_default_value('16px')
			->set_width(25),
			Field::make( 'text', '__lift_chat_title_size', __( 'Title Size' ) )
			->set_default_value('24px')
			->set_width(25),
			Field::make( 'text', '__lift_chat_content_size', __( 'Font Size' ) )
			->set_default_value('16px')
			->set_width(25),
		);
		return $data;
	}

	public function __copyright() {
		$data = array();
		$data = array(
	
			Field::make( 'html', 'crb_html_2', __( 'Section Description' ) )
					->set_html('
					
					<p><img src="'.plugin_dir_url(__DIR__) .'admin/img/logo.png"></p>
					<h1>Website Design and Web Development</h1>
					<h3 style="margin-top:0;">LIFT the Marketing Agency | Create Something Great.</h3>
					<p>Sharing your brand-vision through beautiful web page design and website development for higher rankings and conversions.</p>
					<p>The highest level of creative and professional engagement is what our crew brings to the brands we love. Branding, Website Design, Website Development, Social Media, Search Engine Optimization (SEO), Paid Search (PPC) and Video.
					</p>
					<p>LIFT Creations is a marketing agency that creates beautiful websites that are optimized for SEO and structured for higher conversions. Creative content and quality execution for digital design, web development, social media, search engine optimization, paid search, print, and video.</p>
					<p style="margin-top:0;margin-bottom:0"><strong>Email:</strong> <a href="mailto:hello@liftcreations.com" target="_blank">hello@liftcreations.com</a></p>
					<p style="margin-top:0;margin-bottom:0"><strong>Website:</strong> <a href="https://liftcreations.com/" target="_blank">liftcreations.com</a></p>
					<p style="margin-top:0;margin-bottom:0"><strong>Call Us:</strong> <a href="tel:866-244-1150" target="_blank">866-244-1150</a></p>
					
					'),
					Field::make( 'separator', 'crb_separator_1', __( 'Copyright' ) ),

			Field::make( 'html', 'crb_html_1', __( 'Section Description' ) )
					->set_html('
					
					<p style="margin-top:0;margin-bottom:0">Copyright by LIFT Creations</p>
					<p style="margin-top:0;margin-bottom:0"><strong>Author:</strong> <a href="https://baonguyenyam.github.io/cv/" target="_blank">Nguyen Pham</a></p>
					
					'),
	
		);
		return $data;
	}
	

}


