<?php # -*- coding: utf-8 -*-

namespace Dicentis\Autoload;

/**
 * Specialized auto-load rule for Dipo_Autoload.
 *
 * @author     toscho
 * @author     obstschale
 * @since      0.1.1
 * @version    0.1.1
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://github.com/inpsyde/multilingual-press/blob/master/inc%2Fautoload%2FMlp_Autoload_Rule.php
 * @package    Dicentis
 * @subpackage Autoload
 */
class Dipo_Autoload_Rule implements Inpsyde_Autoload_Rule_Interface {
	/**
	 * Path to Inpsyde Suite directory.
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
	 * @return boolean
	 */
	public function load( $name ) {
		if ( ! $name = $this->prepare_name( $name ) ) {
			return;
		}

		$directories = [ 'controller', 'model', 'view', 'libraries', 'core' ];
		foreach ( $directories as $main_dir ) {

			if ( ! is_dir( "$this->dir/$main_dir" ) ) {
				continue;
			}

			if ( ! is_dir( "$this->dir/$main_dir" ) ) {
				continue;
			}

				$file = "$this->dir/$main_dir/$name.php";

			if ( file_exists( $file ) ) {
				include_once $file;
				return;
			}
		}
	}

	/**
	 * Check for namespaces and matching file name.
	 *
	 * @param  string $name   The class/interface name.
	 * @return string|boolean The class name or FALSE
	 */
	protected function prepare_name( $fully_qualified_name ) {

		$trimed_fqname = trim( $fully_qualified_name, '\\' );
		$exploded_namespace = explode( '\\', $trimed_fqname );
		$name = $exploded_namespace[count( $exploded_namespace ) - 1];
		// Change class name to file name
		// Example: Dipo_Settings -> class-dipo-settings
		$name = strtolower( $name );
		$name = preg_filter( '/_/', '-', $name );
		$name = 'class-' . $name;

		if ( 0 !== strpos( $name, 'class-' ) && 0 !== strpos( $name, 'Inpsyde_' ) ) {
			return false;
		}

		return $name;
	}
}