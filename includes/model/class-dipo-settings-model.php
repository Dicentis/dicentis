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

	public function init_settings() {
		$show_model = new \Dicentis\Podcast_Post_Type\Dipo_Podcast_Shows_Model();
		$shows = $show_model->get_shows( false );

		foreach ( $shows as $show ) {
			$this->register_show_settings( $show->term_id );
		}
	}

	public function register_show_settings( $term_id ) {
		$term = get_term( $term_id, 'podcast_show' );
		$option = 'dipo_' . $term->slug . '_options';

		// register settings for new show
		register_setting(
			$option,
			$option,
			array( $this, 'validate_show_options' ) );

		$sec_id = $this->add_show_settings_section( $term );
		$this->add_show_settings_fields( $term, $sec_id );
	}

	public function add_show_settings_section( $term ) {

		$id = "dipo_{$term->slug}_sec";
		// section settings for new show
		add_settings_section(
			$id,
			__( 'General Settings2', $this->textdomain ), // title
			array( $this, 'general_settings_description' ),
			"dipo_{$term->slug}"
		);

		return $id;
	}

	public function add_show_settings_fields( $term, $sec_id ) {
		// General Fields
		add_settings_field(
			'dipo_show_assets_url',
			__( 'Assets URL2', $this->textdomain ),
			array( $this, 'general_assets_url' ),
			"dipo_{$term->slug}",
			$sec_id,
			array( 'label_for' => 'dipo_itunes_cat1',
				'term_slug' => $term->slug )
		);
	}

	public function general_settings_description() { ?>
		<p>
		<?php _e( 'These settings are global and are used by all podcast shows if no local settings are defined.', $this->textdomain ); ?>
		</p>
	<?php }

	public function general_assets_url( $args ) {
		$opt_name = "dipo_{$args['term_slug']}_options";
		$options = get_option( $opt_name );
		$assets = ( isset( $options['show_assets_url'] ) ) ? $options['show_assets_url'] : '' ;

		// echo the field ?>
		<input id='dipo_show_assets_url' name="<?php echo $opt_name; ?>[show_assets_url]" size='40' type='text' value='<?php echo $assets; ?>' />
		<p class="description"><?php _e('This URL will be prefix the medialinks of episodes', $this->textdomain ); ?></p>
	<?php }

	public function validate_show_options( $input ) {
		$valid = array();

		$valid['show_assets_url'] = esc_url( $input['show_assets_url'] );

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