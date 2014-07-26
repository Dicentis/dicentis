<?php

namespace Dicentis\Core;

require_once 'interface-dipo-property-interface.php';

class Dipo_Property_List implements Dipo_Property_Interface {

	private static $instance = null;

	private $properties = array();

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new Dipo_Property_List();
		}

		return self::$instance;

	}

	public function set( $name, $value ) {

		if ( $this->has( $name ) ) {
			return false;
		} else if ( null == $value ) {
			return false;
		}

		$this->properties[ $name ] = $value;

		return $this;
	}

	public function get( $name ) {

		if ( $this->has( $name ) ) {
			return $this->properties[ $name ];
		} else {
			return false;
		}

	}

	public function has( $name ) {

		return isset( $this->properties[$name] );

	}

	public function remove( $name ) {

		if ( $this->has( $name ) ) {
			unset( $this->properties[$name] );
		}

		return $this;
	}
}