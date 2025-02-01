<?php

namespace App\Inc;

trait SingletonTrait {
	private static array $instances = [];

	/**
	 * Prevent direct instantiation.
	 */
	private function __construct() {}

	/**
	 * Get the single instance of the class.
	 */
	public static function get_instance(): static {
		$class = static::class;
		if (!isset(self::$instances[$class])) {
			self::$instances[$class] = new static();
		}
		return self::$instances[$class];
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 */
	public function __wakeup() {
		throw new \Exception("Cannot unserialize a singleton.");
	}
}