<?php

namespace Dicentis\Dipo_Podcast_Post_Type;

use Dicentis\Settings\Dipo_Settings_Model;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
 * Controller class for episodes and their mediafiles
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @since 0.2.2
 * @package Dicentis
 */
class Dipo_Show_Model {

    /**
	 * Property object with information for Dicentis
	 *
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Property_List $properties includes useful information e.g. textdomain
	 */
	private $properties;

	/**
	 * Textdomain for this plugin
	 *
	 * @since  0.1.0
	 * @access private
	 * @var String $textdomain
	 */
	private $textdomain;

	private $show_slug = null;

	private $show_options = null;

	public function __construct() {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_hooks();
	}

	public function register_hooks() {
	   // register hooks for this class
	}

	public function set_slug( $slug ) {
		$this->show_slug = $slug;
		$this->update();
	}

	public function get_slug() {
		return $this->show_slug;
	}

	public function set_show_options( $options ) {
		$this->show_options = $options;
	}

	public function get_show_options() {
		return $this->show_options;
	}

	public function get_name() {
		return get_term_by( 'slug', $this->get_slug(), 'podcast_show' )->name;
	}

	public function get_description() {
		return get_term_by( 'slug', $this->get_slug(), 'podcast_show' )->description;
	}

	public function update() {
		$show_option_name = Dipo_Settings_Model::get_option_name( $this->get_slug() );
		$show_options     = get_option( $show_option_name );
		$all_show_options = get_option( 'dipo_all_shows_options' );

		if ( $show_options ) {
			foreach ( $show_options as $key => $value ) {
				if ( '' == $show_options[$key] ) {
					$show_options[$key] = $all_show_options[$key];
				}
			}
		} else {
			$show_options = $all_show_options;
		}

		$this->set_show_options( $show_options );

		return null;
	}


	public function get_option_by_key( $key ) {
		$options = $this->get_show_options();
		if ( null !=  $options && isset( $options[$key] ) ) {
			return $options[$key];
		} else {
			return '';
		}
	}

	public function get_cover_art() {
		$coverart = $this->get_option_by_key( 'itunes_coverart' );

		if ( ! isset( $coverart ) || empty( $coverart ) ) {
			// @TODO: double dirname. Can this be refactored?
			$coverart = plugins_url( 'assets/img/cover-art.jpg' , dirname( dirname( __FILE__ ) ) );
		}

		return $coverart;
	}

	public function print_itunes_categories() {
		require_once DIPO_LIB_DIR . '/itunes-categories.php';

		$podcast_category1 = '';
		$podcast_category2 = "\t";
		$podcast_category3 = "\t";

		foreach ( $cats as $catname => $subcats ) :
			$catvalue = strtolower( $catname );
			$cat_text = htmlspecialchars( $catname );

			if ( $this->get_option_by_key( 'itunes_category1') == $catvalue ) {
				$podcast_category1 .= "<itunes:category text='$cat_text' />\n";
			} else if ( $this->get_option_by_key( 'itunes_category2') == $catvalue ) {
				$podcast_category2 .= "<itunes:category text='$cat_text' />\n";
			} else if ( $this->get_option_by_key( 'itunes_category3') == $catvalue ) {
				$podcast_category3 .= "<itunes:category text='$cat_text' />\n";
			} else {
				foreach ( $subcats as $subcat => $subcatname ) :
					$subcatvalue = strtolower( $subcatname );
					$parent = htmlspecialchars( $catname );
					$child = htmlspecialchars( $subcatname );

					if ( $this->get_option_by_key( 'itunes_category1') == $subcatvalue ) {
						$podcast_category1 .= "<itunes:category text='$parent'>";
						$podcast_category1 .= "<itunes:category text='$child' />";
						$podcast_category1 .= "</itunes:category>\n";
					} else if ( $this->get_option_by_key( 'itunes_category2') == $subcatvalue ) {
						$podcast_category2 .= "<itunes:category text='$parent'>";
						$podcast_category2 .= "<itunes:category text='$child' />";
						$podcast_category2 .= "</itunes:category>\n";
					} else if ( $this->get_option_by_key( 'itunes_category3') == $subcatvalue ) {
						$podcast_category3 .= "<itunes:category text='$parent'>";
						$podcast_category3 .= "<itunes:category text='$child' />";
						$podcast_category3 .= '</itunes:category>';
					}
				endforeach;
			}
		endforeach;

		echo $podcast_category1;
		echo $podcast_category2;
		echo $podcast_category3;
	}
}
