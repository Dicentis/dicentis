<?php # -*- coding: utf-8 -*-

namespace Dicentis\Autoload;

/**
 * Basic interface to implement autoload rules.
 *
 * These autoload files are used in multiple projects,
 * hence the different package name.
 *
 * Based on an article by Tom Butler:
 *
 * @author     toscho
 * @since      2013.08.18
 * @version    2013.08.22
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://r.je/php-psr-0-pretty-shortsighted-really.html
 * @link       https://github.com/inpsyde/multilingual-press/blob/master/inc%2Fautoload%2FInpsyde_Autoload_Rule_Interface.php
 * @package    Dicentis
 * @subpackage Autoload
 */
interface Inpsyde_Autoload_Rule_Interface {
	/**
	 * Parse class/trait/interface name and load file.
	 *
	 * @param  string $name
	 * @return boolean
	 */
	public function load( $name );
}