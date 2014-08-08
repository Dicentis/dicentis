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

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
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
			'general' => 'General',
			'itunes' => 'iTunes',
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
		<?php _e( 'Dicentis Podcast Settings', 'dicentis' ); ?>
		</h2><?php

		if ( ! isset( $_GET['page'] ) || esc_attr( $_GET['page'] ) != 'dicentis_settings' ) return;

		if ( isset ( $_GET['tab'] ) ) {
			$tab = esc_attr( $_GET['tab'] );
		} else {
			$tab = 'general';
		}

		$this->setting_tabs( $tab );

		if ( $pagenow == 'edit.php'&& esc_attr( $_GET['page'] ) == 'dicentis_settings' ) {

			switch ( $tab ) {
				default:
				case 'general':
					include( $this->properties->get( 'dipo_templates' ) . '/settings-general-template.php' );
					break;

				case 'itunes':
					include( $this->properties->get( 'dipo_templates' ) . '/settings-itunes-template.php' );
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
}