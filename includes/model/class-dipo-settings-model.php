<?php

namespace Dicentis\Settings;

use Dicentis\Core;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
* Settings page for dicentis plugin
*/
class Dipo_Settings_Model {

	private $properties;
	private $textdomain;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
	}

	private function get_option_name( $slug ) {
		return 'dipo_' . $slug . '_options';
	}

	public function init_settings() {
		$show_model = new \Dicentis\Podcast_Post_Type\Dipo_Podcast_Shows_Model();
		$shows = $show_model->get_shows( false );

		$this->register_show_settings( -1 );
		foreach ( $shows as $show ) {
			$this->register_show_settings( $show->term_id );
		}
	}

	public function register_show_settings( $term_id ) {
		if ( -1 < $term_id ) {
			$term = get_term( $term_id, 'podcast_show' );
			$slug = $term->slug;
		} else {
			$slug = 'all_shows';
		}


		$option = $this->get_option_name( $slug );

		// register settings for new show
		register_setting(
			$option,
			$option,
			array( $this, 'validate_show_options' ) );

		$sec_id = $this->add_show_settings_section( $slug );
	}

/**
  /$$$$$$                        /$$     /$$                              
 /$$__  $$                      | $$    |__/                              
| $$  \__/  /$$$$$$   /$$$$$$$ /$$$$$$   /$$  /$$$$$$  /$$$$$$$   /$$$$$$$
|  $$$$$$  /$$__  $$ /$$_____/|_  $$_/  | $$ /$$__  $$| $$__  $$ /$$_____/
 \____  $$| $$$$$$$$| $$        | $$    | $$| $$  \ $$| $$  \ $$|  $$$$$$ 
 /$$  \ $$| $$_____/| $$        | $$ /$$| $$| $$  | $$| $$  | $$ \____  $$
|  $$$$$$/|  $$$$$$$|  $$$$$$$  |  $$$$/| $$|  $$$$$$/| $$  | $$ /$$$$$$$/
 \______/  \_______/ \_______/   \___/  |__/ \______/ |__/  |__/|_______/ 
 */

	public function add_show_settings_section( $slug ) {

		$id = "dipo_{$slug}_sec";
		// section settings for new show
		add_settings_section(
			$id,
			__( 'General Settings', $this->textdomain ), // title
			array( $this, 'general_settings_description' ),
			"dipo_{$slug}"
		);
		$this->add_show_settings_fields( $slug, $id );

		$id = "dipo_{$slug}_iTunes_sec";
		add_settings_section(
			$id,
			__( 'iTunes Settings', $this->textdomain ),
			array( $this, 'itunes_settings_description' ),
			"dipo_{$slug}_iTunes"
		);
		$this->add_show_itunes_fields( $slug, $id );

	}

	public function general_settings_description() { ?>
		<p>
		<?php _e( 'These settings are global and are used by all podcast shows if no local settings are defined.', $this->textdomain ); ?>
		</p>
	<?php }

	public function itunes_settings_description() { ?>
		<p>
		<?php _e( 'These settings are global and are used by all podcast shows if no local settings are defined.', $this->textdomain ); ?>
		</p>
	<?php }

/**
 /$$$$$$$$ /$$           /$$       /$$          
| $$_____/|__/          | $$      | $$          
| $$       /$$  /$$$$$$ | $$  /$$$$$$$  /$$$$$$$
| $$$$$   | $$ /$$__  $$| $$ /$$__  $$ /$$_____/
| $$__/   | $$| $$$$$$$$| $$| $$  | $$|  $$$$$$ 
| $$      | $$| $$_____/| $$| $$  | $$ \____  $$
| $$      | $$|  $$$$$$$| $$|  $$$$$$$ /$$$$$$$/
|__/      |__/ \_______/|__/ \_______/|_______/ 
 */
	public function add_show_settings_fields( $slug, $sec_id ) {
		// General Fields
		add_settings_field(
			'dipo_show_assets_url',
			__( 'Assets URL', $this->textdomain ),
			array( $this, 'general_assets_url' ),
			"dipo_{$slug}",
			$sec_id,
			array( 'label_for' => 'dipo_itunes_cat1',
				'term_slug' => $slug )
		);
	}


	public function general_assets_url( $args ) {
		$opt_name = $this->get_option_name( $args['term_slug'] );
		$options = get_option( $opt_name );
		$placeholder = '';
		if ( 'all_shows' != $args['term_slug'] ) {
			$all_shows_options = get_option( $this->get_option_name( 'all_shows' ) );
			$placeholder = ( isset( $all_shows_options['show_assets_url'] ) ) ? $all_shows_options['show_assets_url'] : '' ;
		}

		$assets = ( isset( $options['show_assets_url'] ) ) ? $options['show_assets_url'] : '' ;

		// echo the field ?>
		<input id='dipo_show_assets_url' name="<?php echo $opt_name; ?>[show_assets_url]" size='40' type='text' value='<?php echo $assets; ?>' placeholder="<?php echo esc_attr( $placeholder ); ?>" />
		<p class="description"><?php _e('This URL will be prefix the medialinks of episodes', $this->textdomain ); ?></p>
	<?php }

	public function add_show_itunes_fields( $slug, $sec_id ) {

		// iTunes Fields
		add_settings_field(
			'dipo_itunes_owner',
			__( 'iTunes Owner', $this->textdomain ),
			array( $this, 'itunes_owner' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_itunes_cat1',
				'term_slug' => $slug )
		);

	}

	public function itunes_owner( $args ) {
		$opt_name = $this->get_option_name( $args['term_slug'] );
		$options = get_option( $opt_name );
		$placeholder = '';
		if ( 'all_shows' != $args['term_slug'] ) {
			$all_shows_options = get_option( $this->get_option_name( 'all_shows' ) );
			$placeholder = ( isset( $all_shows_options['itunes_owner'] ) ) ? $all_shows_options['itunes_owner'] : '' ;
		}

		$value = ( isset( $options['itunes_owner'] ) ) ? $options['itunes_owner'] : '' ;

		// echo the field ?>
		<input id='dipo_itunes_owner' name='<?php echo $opt_name; ?>[itunes_owner]' size='40' type='text' value='<?php echo esc_attr( $value ); ?>' placeholder='<?php echo esc_attr( $placeholder ); ?>' />
		<p class="description"><?php _e('Owner of the podcast for communication specifically about the podcast', $this->textdomain ); ?></p>
	<?php }


/**
 /$$    /$$          /$$ /$$       /$$             /$$              
| $$   | $$         | $$|__/      | $$            | $$              
| $$   | $$ /$$$$$$ | $$ /$$  /$$$$$$$  /$$$$$$  /$$$$$$    /$$$$$$ 
|  $$ / $$/|____  $$| $$| $$ /$$__  $$ |____  $$|_  $$_/   /$$__  $$
 \  $$ $$/  /$$$$$$$| $$| $$| $$  | $$  /$$$$$$$  | $$    | $$$$$$$$
  \  $$$/  /$$__  $$| $$| $$| $$  | $$ /$$__  $$  | $$ /$$| $$_____/
   \  $/  |  $$$$$$$| $$| $$|  $$$$$$$|  $$$$$$$  |  $$$$/|  $$$$$$$
    \_/    \_______/|__/|__/ \_______/ \_______/   \___/   \_______/
 */
	public function validate_show_options( $input ) {
		$valid = array();

		// General
		$valid['show_assets_url'] = esc_url( $input['show_assets_url'] );

		// iTunes
		$valid['itunes_owner'] = sanitize_text_field( $input['itunes_owner'] );

		return $valid;
	}

	public function add_settings_menu( $view ) {

		add_submenu_page(
			'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE, // add to podcast menu
			__( 'dicentis Podcast Settings', $this->textdomain ),
			__( 'Settings' ),
			'manage_options', // capabilities
			'dicentis_settings', // slug
			array( $view, 'render_settings_page' )
		);

	}

}