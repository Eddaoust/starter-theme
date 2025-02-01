<?php

namespace App\Acf;

use App\Inc\HookableInterface;
use App\Inc\SingletonTrait;
use App\Inc\AcfRegistrable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class FieldsManager implements HookableInterface {
	use SingletonTrait;
	private array $fields = [];

	public function __construct() {
		$this->autoload_fields();
		$this->register_hooks();
	}

	public function register_hooks(): void {
		add_action( 'acf/init', [$this, 'register_fields']);
	}

	private function autoload_fields(): void {
		$fields_dir = __DIR__ . '/Field';

		if (!is_dir($fields_dir)) {
			return;
		}

		foreach (scandir($fields_dir) as $file) {
			if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
				$class_name = 'App\\Acf\\Field\\' . pathinfo($file, PATHINFO_FILENAME);

				if (class_exists($class_name)) {
					$instance = new $class_name();
					if ($instance instanceof AcfRegistrable) {
						$this->fields[] = $instance;
					}
				}
			}
		}
	}

	public function register_fields() {

		foreach ($this->fields as $field) {
			if ($field instanceof AcfRegistrable) {

				$field_group = $field->get_fields();

				if (!empty($field_group)) {
					register_extended_field_group($field_group);
				}
			}
		}
	}
}