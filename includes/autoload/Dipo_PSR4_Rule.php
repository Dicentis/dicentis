<?php
/**
 * Created by PhpStorm.
 * User: RetinaObst
 * Date: 22.02.16
 * Time: 18:49
 */

namespace Dicentis\Autoload;

/**
 * PSR4 auto-load rule for Dipo_Autoload.
 *
 * @author     obstschale
 * @since      0.2.6
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Dipo_PSR4_Rule implements Dipo_Autoload_Rule_Interface {

	/**
	 * Path to Dipo Suite directory.
	 *
	 * @type string
	 */
	protected $dir;

	/**
	 * Constructor
	 *
	 * @param string $dir
	 */
	public function __construct( $dir ) {
		$this->dir = $dir;
	}

	/**
	 * Parse class/trait/interface name and load file.
	 *
	 * @param  string $name
	 *
	 * @return boolean
	 */
	public function load( $name ) {
		if ( ! $name = $this->prepare_name( $name ) ) {
			return;
		}

		$file = $this->dir . DIRECTORY_SEPARATOR . $name;

		if ( file_exists( $file ) ) {
			include_once $file;
			return;
		}
	}

	protected function prepare_name( $name ) {
		// Cut vendor name of
		// Ideally "Dicentis/" is cut off because this is not part of the path
		$cut = substr( $name, strpos( $name, "\\" ) + 1 );
		$cut = strtolower( $cut );

		// Replace all back slashes with the correct directory separator
		$path = str_replace( '\\', DIRECTORY_SEPARATOR, $cut ) . '.php';

		return $path;
	}
}
