<?php

namespace Dicentis\Settings;

use Dicentis\Core;
use Dicentis\Feed;

/**
* Settings page for dicentis plugin
*/
class Dipo_Settings_View {

	private $properties;
	private $textdomain;
	private $controller;
	private $model = null;

	public function __construct( $controller ) {
		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->controller = $controller;
	}

	public function set_model( $model ) {
		$this->model = $model;
	}

	public function get_model() {
		if ( is_null( $this->model ) ) {
			$this->set_model( $this->controller->get_model() );
		}

		return $this->model;
	}

	/**
	 * Menu Callback
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', $this->textdomain ) );
		}

		// Render the settings template
		$this->settings_pages();
	} // END public function dicentis_settings_page()

	public function setting_tabs( $current = 'homepage' ) {
		$tabs = array(
			'shows'  => 'Shows',
			'import' => 'Import/Export',
		);
		?>
		<div id="icon-themes" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? ' nav-tab-active' : ''; ?>
			<a class='nav-tab<?php echo $class; ?>' href='?post_type=dipo_podcast&page=dicentis_settings&tab=<?php echo $tab; ?>'><?php echo $name ?></a>
		<?php } ?>
		</h2>
	<?php }

	public function settings_pages(){
		global $pagenow;
		//generic HTML and code goes here
		?><h2>
		<?php _e( 'Dicentis Podcast Settings', $this->textdomain ); ?>
		</h2><?php

		if ( ! isset( $_GET['page'] ) || esc_attr( $_GET['page'] ) != 'dicentis_settings' ) return;

		if ( isset ( $_GET['tab'] ) ) {
			$tab = esc_attr( $_GET['tab'] );
		} else {
			$tab = 'shows';
		}

		$this->setting_tabs( $tab );

		if ( $pagenow == 'edit.php' && esc_attr( $_GET['page'] ) == 'dicentis_settings' ) {

			switch ( $tab ) {
				default:
				case 'shows':
					$show_model = new \Dicentis\Podcast_Post_Type\Dipo_Podcast_Shows_Model();
					$shows = $show_model->get_shows( false );

					$active_show = 'all_shows';
					$active_show_title = 'All Shows';
					if ( isset ( $_GET['show'] ) ) {
						$active_show = esc_attr( $_GET['show'] );
						foreach ( $shows as $show ) {
							if ( 0 == strcmp( $show->slug, $active_show ) ) {
								$active_show_title = $show->name;
							}
						}
					}

					include( $this->properties->get( 'dipo_templates' ) . '/settings-shows-template.php' );
					break;

				case 'import':
					if ( isset( $_POST['dipo_feed_url'] ) and ! empty( $_POST['dipo_feed_url'] ) ) {
						$url = esc_attr( $_POST['dipo_feed_url'] );
						$feed_importer = new Feed\Dipo_Feed_Import( $url );

						if ( isset( $_POST['dipo_feed_match'] ) ) {
							$feed_match = esc_attr( $_POST['dipo_feed_match'] );
							$feed_importer->set_try_match( $feed_match );
						}

						$feed_array = $feed_importer->get_feed_items();
						$show_slug = ( isset( $_POST['dipo_show_select'] ) ) ? $_POST['dipo_show_select'] : '';
						$result = $feed_importer->import_feed( $feed_array[0], $feed_array[1], $show_slug );
					}

					$args = array(
						'orderby'    => 'name',
						'hide_empty' => false,
					);
					$shows = get_terms( 'podcast_show', $args );
					include( $this->properties->get( 'dipo_templates' ) . '/settings-import-template.php' );
					break;
			}
		}
	}


	public function admin_settings_scripts( $hook ) {

		if ( 'dipo_podcast_page_dicentis_settings' !== $hook ) return;

		$this->admin_settings_styles();

		wp_enqueue_script( 'dipo_settings_script',
			DIPO_ASSETS_URL . '/js/dipo_settings.js',
			array( 'jquery' ) );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );

	}

	public function admin_settings_styles() {

		wp_enqueue_style( 'thickbox' );
		wp_register_style( 'dipo_import_feed_style',
			DIPO_ASSETS_URL . '/css/dipo_import_feed.css' );
		wp_enqueue_style( 'dipo_import_feed_style' );

	}

/**
  /$$$$$$            /$$ /$$ /$$                           /$$
 /$$__  $$          | $$| $$| $$                          | $$
| $$  \__/  /$$$$$$ | $$| $$| $$$$$$$   /$$$$$$   /$$$$$$$| $$   /$$  /$$$$$$$
| $$       |____  $$| $$| $$| $$__  $$ |____  $$ /$$_____/| $$  /$$/ /$$_____/
| $$        /$$$$$$$| $$| $$| $$  \ $$  /$$$$$$$| $$      | $$$$$$/ |  $$$$$$
| $$    $$ /$$__  $$| $$| $$| $$  | $$ /$$__  $$| $$      | $$_  $$  \____  $$
|  $$$$$$/|  $$$$$$$| $$| $$| $$$$$$$/|  $$$$$$$|  $$$$$$$| $$ \  $$ /$$$$$$$/
 \______/  \_______/|__/|__/|_______/  \_______/ \_______/|__/  \__/|_______/
 */

	/* Section-Callbacks */
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

	/* Field-Callbacks */
	public function general_assets_url( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'show_assets_url' );
		$placeholder = $model->get_field_value( 'all_shows', 'show_assets_url' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] );
		$description = 'This URL will be prefix the medialinks of episodes';
		$this->echo_description( $description );
	}


	public function itunes_owner( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_owner' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_owner' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] );
		$description = 'Owner of the podcast for communication specifically about the podcast';
		$this->echo_description( $description );
	}

	public function itunes_owner_mail( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_owner_mail' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_owner_mail' );

		$this->echo_input_field( $value, $placeholder, 'email', $args['label_for'] );
		$description = 'Email address of owner for contact options';
		$this->echo_description( $description );
	}

	public function itunes_title_string( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_title' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_title' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] );
		$description = 'Title of podcast show. If multitple shows are defined please use local settings for shows.';
		$this->echo_description( $description );
	}

	public function itunes_subtitle_string( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_subtitle' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_subtitle' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] );
		$description = 'Subtitle of podcast show. If multitple shows are defined please use local settings for shows.';
		$this->echo_description( $description );
	}

	public function itunes_author_string( $args ) {
		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_author' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_author' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] );
		$description = 'The content of this tag is shown in the Artist column in iTunes';
		$this->echo_description( $description );
	}

	public function itunes_language_dropdown( $args ) {
		// get languages codes in ISO 639
		include_once DIPO_LIB_DIR . '/languages.php';

		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_language' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_language' );

		/* if no value for show is set use the placeholder value */
		$replaced = false;
		if ( empty( $value['field_value'] ) ) {
			$value['field_value'] = $placeholder['field_value'];
			$replaced = true;
		} ?>

		<select id='<?php echo $args['label_for']; ?>' name='<?php echo $value['setting_name'] ?>[itunes_language]'>
		<?php foreach ($languages as $key => $val) {
			echo "<option value='$val[1]'";
			echo ( !strcmp( $value['field_value'], $val[1] ) ) ? " selected>" : ">" ;
			echo $val[2] . " ($val[1])";
			echo "</option>";
		} ?>
		</select> <?php

		$this->echo_description( 'The value for <strong>all shows</strong> is used. Fell free to change it for this show.' );
		$description = 'Because iTunes operates sites worldwide, it is critical to specify the language of a podcast';
		$this->echo_description( $description );
	}

	public function itunes_category( $args ) {
		// get itunes categories as array
		require DIPO_LIB_DIR . '/itunes-categories.php';

		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], $args['cat'] );
		$placeholder = $model->get_field_value( 'all_shows', $args['cat'] ); ?>

		<select id="<?php echo $args['label_for']; ?>" name='<?php echo $value['setting_name'] ?>[<?php echo $args['cat']; ?>]'>
			<option value=''>
			<?php _e( 'None', $this->textdomain ); ?>
			</option>

		<?php
		/* if no value for show is set use the placeholder value */
		$replaced = false;
		if ( empty( $value['field_value'] ) ) {
			$value['field_value'] = $placeholder['field_value'];
			$replaced = true;
		}

		foreach ($cats as $catname => $subcats) {
			// list main cats
			$catvalue = strtolower( $catname );
			echo "<option value='$catvalue'";
			echo ( !strcmp( $value['field_value'], $catvalue ) ) ? " selected>" : ">" ;
			echo $catname;
			echo "</option>";

			foreach ($subcats as $subcat => $subcatname) {
				$subcatvalue = strtolower( $subcatname );
				echo "<option value='$subcatvalue'";
				echo ( !strcmp( $value['field_value'], $subcatvalue ) ) ? " selected>" : ">" ;
				echo $catname . " &gt; " . $subcatname;
				echo "</option>";
			}
		} ?>
		</select>
	<?php if ( $replaced ) {
		$this->echo_description( 'The value for <strong>all shows</strong> is used. Fell free to change it for this show.' );
		}
	}

	public function itunes_copyright( $args ) {

		$model = $this->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_copyright' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_copyright' );

		$this->echo_input_field( $value, $placeholder, 'text', $args['label_for'] ); ?>
		<p class="description">
			<span class="button hide-if-no-js dipo_copyright" data-copyright="&#xA9;">&#xA9;</span>
			<span class="button hide-if-no-js dipo_copyright" data-copyright="&#x2122;">&#x2122;</span>
			<?php _e('State your copyright for the podcasts', $this->textdomain ); ?>
		</p>

	<?php }

	public function itunes_coverart( $args ) {
		// RSS Model Object for Coverlink
		$show_model = new \Dicentis\Dipo_Podcast_Post_Type\Dipo_Show_Model();

		$model = $this->controller->get_model();

		$value = $model->get_field_value( $args['term_slug'], 'itunes_coverart' );
		$placeholder = $model->get_field_value( 'all_shows', 'itunes_coverart' ); ?>

		<input id="dipo_itunes_coverart" type="text" size="36" name="<?php echo $value['setting_name'] ?>[itunes_coverart]"
			value="<?php echo esc_url( $value['field_value'] ); ?>" placeholder="<?php echo esc_url( $placeholder['field_value'] ); ?>" />
		<div id="dipo_upload_image_button" class="button"><?php _e( 'Upload Image', $this->textdomain ); ?></div>
		<?php

		$description = 'Enter an URL or upload an image for the cover art.';
		$this->echo_description( $description );?>

		<p class="description" ><?php echo sprintf( __( "If no image URL is given, <a href='%s' title='Podcast Coverart'>this</a> fallback is used.", $this->textdomain ), $show_model->get_cover_art() ); ?></p>

	<?php }

	public function echo_input_field( $value, $placeholder, $type = 'text', $label = '') {
		// echo the field ?>
		<input id="<?php echo $label; ?>" name="<?php echo $value['setting_name']; ?>[<?php echo $value['field_name']; ?>]" size='40' type='<?php echo $type; ?>' value='<?php echo $value['field_value']; ?>' placeholder="<?php echo esc_attr( $placeholder['field_value'] ); ?>" />
	<?php }

	public function echo_description( $desc ) { ?>
		<p class="description"><?php _e( $desc, $this->textdomain ); ?></p>
	<?php }

}
