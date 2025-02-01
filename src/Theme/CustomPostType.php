<?php

namespace App\Theme;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class CustomPostType implements HookableInterface {
	use SingletonTrait;

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action('init', [$this, 'register_post_type']);
	}

	/**
	 * This is where you can register custom post type
	 * https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
	 *
	 * @return void
	 */
	public function register_post_type(): void {}
}