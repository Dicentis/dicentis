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
	private $controller;

	public function __construct( $controller ) {
		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->controller = $controller;
	}

	public static function get_option_name( $slug ) {
		return 'dipo_' . $slug . '_options';
	}

	public function get_field_value( $setting_name, $value_name ) {

		$opt_name = $this->get_option_name( $setting_name );

		$options = get_option( $opt_name );
		// if ( 'all_shows' != $args['term_slug'] ) {
		// 	$all_shows_options = get_option( $model->get_option_name ( 'all_shows' ) );
		// 	$placeholder = ( isset( $all_shows_options['show_assets_url'] ) ) ? $all_shows_options['show_assets_url'] : '' ;
		// }

		$value = ( isset( $options[$value_name] ) ) ? $options[$value_name] : '' ;

		return array(
			'setting_name' => $opt_name,
			'field_name'   => $value_name,
			'field_value'  => $value
		);
	}

	public function get_option_by_key( $setting_name, $value_name ) {
		if ( !isset( $setting_name ) || !isset( $value_name ) ) {
			return '';
		}

		$option_slug = $this->get_option_name( $setting_name );
		$value = $this->get_field_value( $option_slug, $value_name );

		if ( empty( $value ) || !isset( $value ) ) {
			$option_slug = $this->get_option_name( 'all_shows' );
			$value = $this->get_field_value( $option_slug, $value_name );
		}

		return $value;
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
		$view = $this->controller->get_view();

		$id = "dipo_{$slug}_sec";
		// section settings for new show
		add_settings_section(
			$id,
			__( 'General Settings', $this->textdomain ), // title
			array( $view, 'general_settings_description' ),
			"dipo_{$slug}"
		);
		$this->add_show_settings_fields( $slug, $id );

		$id = "dipo_{$slug}_iTunes_sec";
		add_settings_section(
			$id,
			__( 'iTunes Settings', $this->textdomain ),
			array( $view, 'itunes_settings_description' ),
			"dipo_{$slug}_iTunes"
		);
		$this->add_show_itunes_fields( $slug, $id );

	}

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
		$view = $this->controller->get_view();

		// General Fields
		add_settings_field(
			'dipo_show_assets_url',
			__( 'Assets URL', $this->textdomain ),
			array( $view, 'general_assets_url' ),
			"dipo_{$slug}",
			$sec_id,
			array( 'label_for' => 'dipo_label_show_asset',
				'term_slug' => $slug )
		);
	}

	public function add_show_itunes_fields( $slug, $sec_id ) {
		$view = $this->controller->get_view();

		// iTunes Fields
		add_settings_field(
			'dipo_itunes_owner',
			__( 'iTunes Owner', $this->textdomain ),
			array( $view, 'itunes_owner' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_owner',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_owner_mail',
			__( 'iTunes Owner E-Mail', $this->textdomain ),
			array( $view, 'itunes_owner_mail' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_mail',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_title',
			__( 'iTunes Title', $this->textdomain ),
			array( $view, 'itunes_title_string' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_title',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_subtitle',
			__( 'iTunes Subtitle', $this->textdomain ),
			array( $view, 'itunes_subtitle_string' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_subtitle',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_author',
			__( 'iTunes Author', $this->textdomain ),
			array( $view, 'itunes_author_string' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_author',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_language',
			__( 'iTunes Language', $this->textdomain ),
			array( $view, 'itunes_language_dropdown' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_lang',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_cat1',
			__( 'iTunes Category 1', $this->textdomain ),
			array( $view, 'itunes_category' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_cat1',
				'cat' => 'itunes_category1',
				'term_slug' => $slug  )
		);

		add_settings_field(
			'dipo_itunes_cat2',
			__( 'iTunes Category 2', $this->textdomain ),
			array( $view, 'itunes_category' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_cat2',
				'cat' => 'itunes_category2',
				'term_slug' => $slug  )
		);

		add_settings_field(
			'dipo_itunes_cat3',
			__( 'iTunes Category 3', $this->textdomain ),
			array( $view, 'itunes_category' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_cat3',
				'cat' => 'itunes_category3',
				'term_slug' => $slug  )
		);

		add_settings_field(
			'dipo_itunes_copyright',
			__( 'iTunes Copyright', $this->textdomain ),
			array( $view, 'itunes_copyright' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_itunes_copyright',
				'term_slug' => $slug )
		);

		add_settings_field(
			'dipo_itunes_coverart',
			__( 'iTunes Cover Art', $this->textdomain ),
			array( $view, 'itunes_coverart' ),
			"dipo_{$slug}_iTunes",
			$sec_id,
			array( 'label_for' => 'dipo_label_itunes_coverart',
				'term_slug' => $slug )
		);

	}

	public function get_show_option( $value_name, $show_slug, $use_fallback = false ) {

		$options = get_option( $this->get_option_name( $show_slug ) );
		$option = ( isset( $options[$value_name] ) ) ? $options[$value_name] : '' ;

		if ( empty( $option ) && $use_fallback ) {
			$all_shows_options = get_option( $this->get_option_name( 'all_shows' ) );
			$placeholder = ( isset( $all_shows_options[$value_name] ) ) ? $all_shows_options[$value_name] : '' ;
		}

		return $option;
	}

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

    /**
     * [validate_show_options description]
     *
     * @link http://codex.wordpress.org/Data_Validation
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
	public function validate_show_options( $input ) {
		$valid = array();

		// General
		$valid['show_assets_url'] = esc_url_raw( $input['show_assets_url'] );

		// iTunes
		$valid['itunes_owner'] = sanitize_text_field( $input['itunes_owner'] );
		$valid['itunes_owner_mail'] = sanitize_email( $input['itunes_owner_mail'] );
		$valid['itunes_title'] = sanitize_text_field( $input['itunes_title'] );
		$valid['itunes_subtitle'] = sanitize_text_field( $input['itunes_subtitle'] );
		$valid['itunes_author'] = sanitize_text_field( $input['itunes_author'] );
		preg_match('/[a-z]{1,3}(_[A-Z]{2})?/', $input['itunes_language'], $matches);
		$valid['itunes_language'] = $matches[0];
		$valid['itunes_category1'] = sanitize_text_field( $input['itunes_category1'] );
		$valid['itunes_category2'] = sanitize_text_field( $input['itunes_category2'] );
		$valid['itunes_category3'] = sanitize_text_field( $input['itunes_category3'] );
		$valid['itunes_copyright'] = sanitize_text_field( $input['itunes_copyright'] );
		$valid['itunes_coverart'] = esc_url_raw( $input['itunes_coverart'] );

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
