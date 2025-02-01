<?php

namespace App\Inc;

interface HookableInterface {
	public function register_hooks(): void;
}