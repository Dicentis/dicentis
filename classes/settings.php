<?php
if ( !class_exists('Dicentis_Settings') ) {

	/**
	* Settings page for dicentis plugin
	*/
	class Dicentis_Settings {

		public function __construct() {
			// register actions
			add_action( 'admin_init', array( $this, 'admin_init') );
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
		} // END function __construct() 


		/**
		 * hook into WP's admin_init action hok
		 */
		public function admin_init() {
			// register the settings for this plugin
			// iTunes Settings for RSS Feed
			register_setting( 'dipo_itunes_options', 'dipo_itunes_options', array( $this, 'validate_options' ) );

			// iTunes section settings
			add_settings_section(
				'dipo_itunes_main', // id
				__( 'iTunes Settings', 'dicentis' ), // title
				array( $this, 'itunes_settings_description' ),
				'dipo_itunes'
			);

			add_settings_field(
				'dipo_itunes_owner',
				__( 'iTunes Owner', 'dicentis' ),
				array( $this, 'itunes_owner_string' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_owner_mail',
				__( 'iTunes Owner E-Mail', 'dicentis' ),
				array( $this, 'itunes_owner_mail' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_title',
				__( 'iTunes Title', 'dicentis' ),
				array( $this, 'itunes_title_string' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_subtitle',
				__( 'iTunes Subtitle', 'dicentis' ),
				array( $this, 'itunes_subtitle_string' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_author',
				__( 'iTunes Author', 'dicentis' ),
				array( $this, 'itunes_author_string' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_language',
				__( 'iTunes Language', 'dicentis' ),
				array( $this, 'itunes_language_dropdown' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_cat1',
				__( 'iTunes Category 1', 'dicentis' ),
				array( $this, 'itunes_category1' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_cat2',
				__( 'iTunes Category 2', 'dicentis' ),
				array( $this, 'itunes_category2' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

			add_settings_field(
				'dipo_itunes_cat3',
				__( 'iTunes Category 3', 'dicentis' ),
				array( $this, 'itunes_category3' ),
				'dipo_itunes',
				'dipo_itunes_main'
			);

		} // END public function admin_init()

		public function itunes_settings_description() { ?>
			<p>
			<?php _e( 'These settings are global and are used by all podcast shows if no local settings are defined.', 'dicentis' ); ?>
			</p>
		<?php }

		public function itunes_owner_string() {
			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$owner = $options['itunes_owner'];
			// echo the field ?>
			<input id='dipo_itunes_owner' name='dipo_itunes_options[itunes_owner]' size='40' type='text' value='<?php echo $owner; ?>' />
			<p class="description"><?php _e('Owner of the podcast for communication specifically about the podcast', 'dicentis' ); ?></p>
		<?php }

		public function itunes_owner_mail() {
			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$owner_mail = $options['itunes_owner_mail'];
			// echo the field ?>
			<input id='dipo_itunes_owner_mail' name='dipo_itunes_options[itunes_owner_mail]' size='40' type='mail' value='<?php echo $owner_mail; ?>' />
			<p class="description"><?php _e('Email address of owner for contact options', 'dicentis' ); ?></p>
		<?php }

		public function itunes_title_string() {
			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$text_string = $options['itunes_title'];
			// echo the field ?>
			<input id='dipo_itunes_title' name='dipo_itunes_options[itunes_title]' size='40' type='text' value='<?php echo $text_string; ?>' />
			<p class="description"><?php _e('Title of podcast show. If multitple shows are defined please use local settings for shows.', 'dicentis' ); ?></p>
		<?php }

		public function itunes_subtitle_string() {
			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$text_string = $options['itunes_subtitle'];
			// echo the field ?>
			<input id='dipo_itunes_subtitle' name='dipo_itunes_options[itunes_subtitle]' size='40' type='text' value='<?php echo $text_string; ?>' />
			<p class="description"><?php _e('Subtitle of podcast show. If multitple shows are defined please use local settings for shows.', 'dicentis' ); ?></p>
		<?php }

		public function itunes_author_string() {
			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$author = $options['itunes_author'];
			// echo the field ?>
			<input id='dipo_itunes_author' name='dipo_itunes_options[itunes_author]' size='40' type='text' value='<?php echo $author; ?>' />
			<p class="description"><?php _e('The content of this tag is shown in the Artist column in iTunes', 'dicentis' ); ?></p>
		<?php }

		public function itunes_language_dropdown() {
			// get languages codes in ISO 639
			include_once plugin_dir_path( __FILE__ ) . '../lib/languages.php';

			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$lang = $options['itunes_language']; ?>

			<select id='dipo_itunes_language' name='dipo_itunes_options[itunes_language]'>
			<?php foreach ($languages as $key => $value) {
				echo "<option value='$value[1]'";
				echo ( !strcmp( $lang, $value[1] ) ) ? " selected>" : ">" ;
				echo $value[2] . " ($value[1])";
				echo "</option>";
			} ?>
			</select>
			<p class="description"><?php _e('Because iTunes operates sites worldwide, it is critical to specify the language of a podcast' ); ?></p>
		<?php }

		public function itunes_category1() {
			// get itunes categories as array
			require plugin_dir_path( __FILE__ ) . '../lib/itunes-categories.php';

			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$cat1 = $options['itunes_category1']; ?>

			<select id='dipo_itunes_cat1' name='dipo_itunes_options[itunes_category1]'>
				<option value=''>
				<?php _e( 'None', 'dicentis' ); ?>
				</option>

			<?php foreach ($cats as $catname => $subcats) {
				// list main cats
				$catvalue = strtolower( $catname );
				echo "<option value='$catvalue'";
				echo ( !strcmp( $cat1, $catvalue ) ) ? " selected>" : ">" ;
				echo $catname;
				echo "</option>";

				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					echo "<option value='$subcatvalue'";
					echo ( !strcmp( $cat1, $subcatvalue ) ) ? " selected>" : ">" ;
					echo $catname . " &gt; " . $subcatname;
					echo "</option>";
				}
			} ?>
			</select>
		<?php }

		public function itunes_category2() {
			// get itunes categories as array
			require plugin_dir_path( __FILE__ ) . '../lib/itunes-categories.php';

			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$cat2 = $options['itunes_category2']; ?>

			<select id='dipo_itunes_cat2' name='dipo_itunes_options[itunes_category2]'>
				<option value=''>
				<?php _e( 'None', 'dicentis' ); ?>
				</option>

			<?php foreach ($cats as $catname => $subcats) {
				// list main cats
				$catvalue = strtolower( $catname );
				echo "<option value='$catvalue'";
				echo ( !strcmp( $cat2, $catvalue ) ) ? " selected>" : ">" ;
				echo $catname;
				echo "</option>";

				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					echo "<option value='$subcatvalue'";
					echo ( !strcmp( $cat2, $subcatvalue ) ) ? " selected>" : ">" ;
					echo $catname . " &gt; " . $subcatname;
					echo "</option>";
				}
			} ?>
			</select>
		<?php }

		public function itunes_category3() {
			// get itunes categories as array
			require plugin_dir_path( __FILE__ ) . '../lib/itunes-categories.php';

			// get option 'dipo_itunes_options'
			$options = get_option( 'dipo_itunes_options' );
			$cat3 = $options['itunes_category3']; ?>

			<select id='dipo_itunes_cat3' name='dipo_itunes_options[itunes_category3]'>
				<option value=''>
				<?php _e( 'None', 'dicentis' ); ?>
				</option>

			<?php foreach ($cats as $catname => $subcats) {
				// list main cats
				$catvalue = strtolower( $catname );
				echo "<option value='$catvalue'";
				echo ( !strcmp( $cat3, $catvalue ) ) ? " selected>" : ">" ;
				echo $catname;
				echo "</option>";

				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					echo "<option value='$subcatvalue'";
					echo ( !strcmp( $cat3, $subcatvalue ) ) ? " selected>" : ">" ;
					echo $catname . " &gt; " . $subcatname;
					echo "</option>";
				}
			} ?>
			</select>
		<?php }

		public function validate_options( $input ) {
			$valid = array();

			// TODO: RegEx definieren
			$valid['itunes_owner'] = $input['itunes_owner'];
			$email = $input['itunes_owner_mail'];
			$valid['itunes_owner_mail'] = ( is_email( $email ) ) ? $input['itunes_owner_mail'] : 'n.a.';

			$valid['itunes_title'] = preg_replace(
				'/[^a-zA-Z0-9 ]/',
				'',
				$input['itunes_title']);

			$valid['itunes_subtitle'] = preg_replace(
				'/[^a-zA-Z0-9 ]/',
				'',
				$input['itunes_subtitle']);

			$valid['itunes_author'] = $input['itunes_author'];

			$valid['itunes_language'] = $input['itunes_language'];

			$valid['itunes_category1'] = $input['itunes_category1'];
			$valid['itunes_category2'] = $input['itunes_category2'];
			$valid['itunes_category3'] = $input['itunes_category3'];

			return $valid;
		}

		/**
		 * Add a page to podcast post type to manage
		 * this plugin's settings
		 */
		public function add_menu() {
			add_options_page(
				__( 'dicentis Podcast Settings', 'dicentis' ),
				__( 'dicentis Podcast', 'dicentis' ),
				'manage_options', // capabilities
				'dicentis_settings', // slug
				array( $this, 'dicentis_settings_page' )
			);
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */
		public function dicentis_settings_page() {
			if( !current_user_can('manage_options') ) {
				wp_die( __('You do not have sufficient permissions to access this page.', 'dicentis' ) );
			}

			// Render the settings template
			// include( sprintf( "%s/../templates/settings.php", dirname(__FILE__) ) );
			$this->ilc_settings_page();
		} // END public function dicentis_settings_page()

		public function ilc_admin_tabs( $current='homepage' ) {
			$tabs = array( 'general' => 'General', 'itunes' => 'iTunes' );
			echo '<div id="icon-themes" class="icon32"><br></div>';
			echo '<h2 class="nav-tab-wrapper">';
			foreach($tabs as $tab => $name){
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class' href='?page=dicentis_settings&tab=$tab'>$name</a>";
			}
			echo'</h2>';
		}

		public function ilc_settings_page(){
			global $pagenow;
			//generic HTML and code goes here

			if( isset ( $_GET['tab'] ) ) $this->ilc_admin_tabs( $_GET['tab'] );
			else $this->ilc_admin_tabs( 'general' );

			if( $pagenow == 'options-general.php'&& $_GET['page'] == 'dicentis_settings' ) {
				if( isset ( $_GET['tab'] ) )
					$tab = $_GET['tab'];
				else
					$tab='general';

				switch ( $tab ) {
					case 'general':
						include( sprintf( "%s/../templates/settings-general.php", dirname(__FILE__) ) );
						break;

					case 'itunes':
						include( sprintf( "%s/../templates/settings-itunes.php", dirname(__FILE__) ) );
						break;

					default:
						# code...
						break;
				}
			}
		}
	} // END class Dicentis_Settings
} // END if ( !class_exists('Dicentis_Settings') )
