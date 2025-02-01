<?php

namespace App\Inc;

interface AcfRegistrable {
	public function get_fields(): array;
}