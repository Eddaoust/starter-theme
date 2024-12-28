<?php

/**
 * StarterSite class
 * This class is used to add custom functionality to the theme.
 */

namespace App;

use Timber\Site;
use Timber\Timber;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * Class StarterSite.
 */
class StarterSite extends Site {



	/**
	 * StarterSite constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_supports' ] );
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
		add_action( 'init', [ $this, 'security_configuration']);
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

		add_filter( 'timber/context', [ $this, 'add_to_context' ] );
		add_filter( 'timber/twig/filters', [ $this, 'add_filters_to_twig' ] );
		add_filter( 'timber/twig/functions', [ $this, 'add_functions_to_twig' ] );
		add_filter( 'timber/twig/environment/options', [ $this, 'update_twig_environment_options' ] );

		parent::__construct();
	}

	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types() {}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies() {}

	/**
	 * This is where you can add css & js.
	 */
	public function enqueue_scripts() {
		$manifestPath = get_theme_file_path('dist/.vite/manifest.json');
		// Check if the manifest file exists and is readable before using it
		if (file_exists($manifestPath)) {
			$manifest = json_decode(file_get_contents($manifestPath), true);
			// Check if the file is in the manifest before enqueuing
			if (isset($manifest['assets/scripts/main.js'])) {
				wp_enqueue_script('eddaoust_theme', get_theme_file_uri('dist/' . $manifest['assets/scripts/main.js']['file']), [], '', ['strategy' => 'defer', 'in_footer' => true]);
				// Enqueue the CSS file
				wp_enqueue_style('eddaoust', get_theme_file_uri('dist/' . $manifest['assets/scripts/main.js']['css'][0]));
			}
		}
	}

	/**
	 * This is where you add some context.
	 *
	 * @param array $context context['this'] Being the Twig's {{ this }}
	 */
	public function add_to_context( $context ) {
		$context['menu']  = Timber::get_menu( 'primary_navigation' );
		$context['site']  = $this;

		return $context;
	}

	/**
	 * This is where you can add your theme supports.
	 */
	public function theme_supports() {
		// Register navigation menus
		register_nav_menus(
			[
				'primary_navigation' => _x( 'Main menu', 'Backend - menu name', 'timber-starter' ),
			]
		);

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			[
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			]
		);

		add_theme_support( 'menus' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @link https://timber.github.io/docs/v2/hooks/filters/#timber/twig/filters
	 * @param array $filters an array of Twig filters.
	 */
	public function add_filters_to_twig( $filters ) {

		$additional_filters = [
			'myfoo' => [
				'callable' => [ $this, 'myfoo' ],
			],
		];

		return array_merge( $filters, $additional_filters );
	}


	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @link https://timber.github.io/docs/v2/hooks/filters/#timber/twig/functions
	 * @param array $functions an array of existing Twig functions.
	 */
	public function add_functions_to_twig( $functions ) {
		$additional_functions = [
			'get_theme_mod' => [
				'callable' => 'get_theme_mod',
			],
		];

		return array_merge( $functions, $additional_functions );
	}

	/**
	 * Updates Twig environment options.
	 *
	 * @see https://twig.symfony.com/doc/2.x/api.html#environment-options
	 *
	 * @param array $options an array of environment options
	 *
	 * @return array
	 */
	public function update_twig_environment_options( $options ) {
		// $options['autoescape'] = true;

		return $options;
	}

	/**
	 * Several safety and comfort configurations
	 *
	 * @return void
	 */
	public function security_configuration() {
		/**
		 * Removes x-powered-by header which is set by PHP
		 */
		header_remove('x-powered-by');

		/**
		 * Remove X-Pingback header
		 */
		add_filter('pings_open', function() {
			return false;
		});

		/**
		 * Disables xmlrpc.php
		 * Disable only if your site does not require use of xmlrpc
		 */
		add_filter('xmlrpc_enabled', function() {
			return false;
		});

		/**
		 * Disables REST API completely for non-logged in users
		 * Use this option only if your site does not require use of REST API
		 */
		add_filter('rest_authentication_errors', function($result) {
			return (is_user_logged_in()) ? $result : new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
		});

		/**
		 * Disables Wordpress default REST API endpoints.
		 * Use this option if your plugins require use of REST API, but would still like to disable core endpoints.
		 */
		add_filter('rest_endpoints', function($endpoints) {
			// If user is logged in, allow all endpoints
			if(is_user_logged_in()) {
				return $endpoints;
			}
			foreach($endpoints as $route => $endpoint) {
				if(stripos($route, '/wp/') === 0) {
					unset($endpoints[ $route ]);
				}
			}
			return $endpoints;
		});

		/**
		 * Disable plugins auto-update email notifications
		 */
		add_filter( 'auto_plugin_update_send_email', function() {
			return false;
		});

		/**
		 * Disable themes auto-update email notifications
		 */
		add_filter( 'auto_theme_update_send_email', function() {
			return false;
		});

		/**
		 * Removes unnecessary information from <head> tag
		 */
		add_action('init', function() {
			// Remove post and comment feed link
			remove_action( 'wp_head', 'feed_links', 2 );
			// Remove post category links
			remove_action('wp_head', 'feed_links_extra', 3);
			// Remove link to the Really Simple Discovery service endpoint
			remove_action('wp_head', 'rsd_link');
			// Remove the link to the Windows Live Writer manifest file
			remove_action('wp_head', 'wlwmanifest_link');
			// Remove the XHTML generator that is generated on the wp_head hook, WP version
			remove_action('wp_head', 'wp_generator');
			// Remove start link
			remove_action('wp_head', 'start_post_rel_link');
			// Remove index link
			remove_action('wp_head', 'index_rel_link');
			// Remove previous link
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);
			// Remove relational links for the posts adjacent to the current post
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
			// Remove relational links for the posts adjacent to the current post
			remove_action('wp_head', 'wp_oembed_add_discovery_links');
			// Remove REST API links
			remove_action('wp_head', 'rest_output_link_wp_head');
			// Remove Link header for REST API
			remove_action('template_redirect', 'rest_output_link_header', 11, 0 );
			// Remove Link header for shortlink
			remove_action('template_redirect', 'wp_shortlink_header', 11, 0 );
			// If you're not using emojis, you can remove the additional JavaScript & CSS used for emoji support
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
			// If your site and plugins don’t rely on deprecated jQuery functions, you can dequeue jquery-migrate
			add_action('wp_default_scripts', function( $scripts ) {
				if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
					$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, ['jquery-migrate'] );
				}
			});
		});

		/**
		 * List of feeds to disable
		 */
		$feeds = [
			'do_feed',
			'do_feed_rdf',
			'do_feed_rss',
			'do_feed_rss2',
			'do_feed_atom',
			'do_feed_rss2_comments',
			'do_feed_atom_comments',
		];

		foreach($feeds as $feed) {
			add_action($feed, function() {
				wp_die('Feed has been disabled.');
			}, 1);
		}

		/**
		 * Remove wp-embed.js file from loading
		 */
		add_action( 'wp_footer', function() {
			wp_deregister_script('wp-embed');
		});

		/**
		 * Enable WebP image type upload
		 */
		add_filter('mime_types', function($existing_mimes) {
			$existing_mimes['webp'] = 'image/webp';
			return $existing_mimes;
		});

		/**
		 * Display WebP thumbnail
		 */
		add_filter('file_is_displayable_image', function($result, $path) {
			return ($result) ? $result : (empty(@getimagesize($path)) || !in_array(@getimagesize($path)[2], [IMAGETYPE_WEBP]));
		}, 10, 2);

		/**
		 * Disabled autoupdate
		 */
		add_filter( 'auto_update_plugin', function() {
			return false;
		});
		add_filter( 'auto_update_theme', function() {
			return false;
		});
		add_filter( 'auto_update_core', function() {
			return false;
		});
	}
}
