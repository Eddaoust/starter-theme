<?php

namespace App\Theme;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Menu implements HookableInterface {
	use SingletonTrait;

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action('init', [ $this, 'register_menus']);
	}

	/**
	 * This is where you can register nav menus
	 * https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
	 *
	 * @return void
	 */
	public function register_menus(): void {}
}