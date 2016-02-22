<?php # -*- coding: utf-8 -*-

namespace Dicentis\Autoload;

/**
 * Set up auto-loader or load all available files immediately for PHP < 5.3.
 *
 * @author     toscho
 * @author     obstschale
 * @since      0.1.1
 * @version    0.1.1
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    Dicentis
 * @subpackage Autoload
 */
class Dipo_Load_Controller {

	/**
	 * Path to plugin files
	 *
	 * @var string
	 */
	private $plugin_dir;

	/**
	 * Instance of Dipo_Autoload
	 *
	 * @var Dipo_Autoload
	 */
	private $loader;

	/**
	 * Constructor
	 *
	 * @param string $plugin_dir
	 */
	public function __construct( $plugin_dir ) {

		$this->plugin_dir = $plugin_dir;

		// Can be turned off in PHP 5.2. We ignore that.
		$this->setup_autoloader();
	}

	/**
	 * Return current instance of autoloader.
	 *
	 * @return Dipo_Autoload
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Real auto-loader for modern PHP installations.
	 *
	 * @return void
	 */
	private function setup_autoloader() {

		$dir = dirname( __FILE__ );

		// We need these classes in exactly this order
		if ( ! interface_exists( 'Dipo_Autoload_Rule_Interface' ) ) {
			require "$dir/Dipo_Autoload_Rule_Interface.php";
		}

		if ( ! class_exists( 'Dipo_Autoload_Rule' ) ) {
			require "$dir/Dipo_Autoload_Rule_old.php";
		}

		if ( ! class_exists( 'Dipo_PSR4_Rule' ) ) {
			require "$dir/Dipo_PSR4_Rule.php";
		}

		if ( ! class_exists( 'Dipo_Autoload' ) ) {
			require "$dir/Dipo_Autoload.php";
		}

		$this->loader = new Dipo_Autoload;
		$rule         = new Dipo_Autoload_Rule_Old( $this->plugin_dir );
		$this->loader->add_rule( $rule );

		$psr4_rule         = new Dipo_PSR4_Rule( $this->plugin_dir );
		$this->loader->add_rule( $psr4_rule );
	}
}
