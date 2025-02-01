<?php

namespace App\Theme;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Taxonomy implements HookableInterface {
	use SingletonTrait;

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action( 'init', [ $this, 'register_taxonomies']);
	}

	/**
	 * This is where you can add custom taxonomies
	 * https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @return void
	 */
	public function register_taxonomies(): void {}
}