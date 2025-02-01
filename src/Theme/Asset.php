<?php

namespace App\Theme;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Asset implements HookableInterface {
	use SingletonTrait;

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	public function enqueue_scripts(): void {
		$manifestPath = get_theme_file_path('dist/.vite/manifest.json');
		// Check if the manifest file exists and is readable before using it
		if (file_exists($manifestPath)) {
			$manifest = json_decode(file_get_contents($manifestPath), true);
			// Check if the file is in the manifest before enqueuing
			if (isset($manifest['assets/scripts/main.js'])) {
				wp_enqueue_script('eddaoust', get_theme_file_uri('dist/' . $manifest['assets/scripts/main.js']['file']), [], '', ['strategy' => 'defer', 'in_footer' => true]);
				// Enqueue the CSS file
				wp_enqueue_style('eddaoust', get_theme_file_uri('dist/' . $manifest['assets/scripts/main.js']['css'][0]));
			}
		}
	}
}