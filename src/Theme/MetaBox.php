<?php

namespace App\Theme;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;

if (!defined( 'ABSPATH')) {
	exit; // Exit if accessed directly.
}

final class MetaBox implements HookableInterface {
	use SingletonTrait;

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
		add_action('save_post', [ $this, 'save_post_meta']);
	}

	/**
	 * This is where you can register meta boxes
	 * https://developer.wordpress.org/reference/functions/add_meta_box/
	 *
	 * @return void
	 */
	public function register_meta_boxes(): void {}

	public function save_post_meta(): void {}
}