<?php

namespace App\Acf\Field;

use App\Inc\AcfRegistrable;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class ExampleHomePageFields implements AcfRegistrable {

	public function get_fields(): array {
		return [
			'title' => 'Homepage ACF Fields',
			'fields' => [
				Tab::make('ACF Tab'),
				Text::make('Title')
				    ->required(),
			],
			'location' => [
				Location::where('post_type', 'post')
			],
		];
	}
}