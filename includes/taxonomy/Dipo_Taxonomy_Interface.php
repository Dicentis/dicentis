<?php

namespace Dicentis\Taxonomy;

/**
 * Interface Dipo_Taxonomy_Interface
 *
 * @author  Hans-Helge Buerger
 * @since   0.2.6
 * @package Dicentis\Taxonomy
 */
interface Dipo_Taxonomy_Interface {

	/**
	 * Function to register the taxonomy
	 */
	public function register_taxonomy();

	/**
	 * Initialize term meta for taxonomy
	 */
	public function init_term_meta();

}
