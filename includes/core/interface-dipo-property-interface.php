<?php

namespace Dicentis\Core;

/**
 * Simple property interface
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @package Dicentis
 * @version 0.2.0
 */
interface Dipo_Property_Interface {

	/**
	 * Singleton method to get property object
	 * 
	 * @return Dipo_Properties_Interface instance of singleton object
	 */
	public static function get_instance();

	/**
	 * Simple Setter method to add new variable by name
	 * 
	 * @param String $name  Name of new variable
	 * @param mixed  $value Can be anything; Simple string or complex object
	 */
	public function set( $name, $value );

	/**
	 * Simple Getter method to get a varibale by name
	 * 
	 * @param  String $name Name of variable
	 * @return mixed        Value of variable
	 */
	public function get( $name );

	/**
	 * Method to check if variable by name exists
	 * 
	 * @param  String  $name Name of variable
	 * @return boolean       true if variable is set, otherwise false
	 */
	public function has( $name );

	/**
	 * Delete method to remove a variable by name
	 * 
	 * @param  String $name Name of variable
	 */
	public function remove( $name );
}