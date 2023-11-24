<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldTypeDatabaseSeeder extends Seeder
{
	/**
	 * Seed the field_types database.
	 */
	public function run(): void
	{
		$date = Carbon::now();

		DB::table('field_types')->insert([
			[
				'name' => 'Textbox',
				'type' => 'text_single',
				'template' => 'text_single',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Textbox (Multiline)',
				'type' => 'text_multi',
				'template' => 'text_multi',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Number',
				'type' => 'number',
				'template' => 'number',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Checkbox',
				'type' => 'checkbox',
				'template' => 'checkbox',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Radio',
				'type' => 'radio',
				'template' => 'radio',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Dropdown',
				'type' => 'select',
				'template' => 'select',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Score',
				'type' => 'number_rating',
				'template' => 'number_rating',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Likert scale',
				'type' => 'likert',
				'template' => 'likert',
				'created_at' => $date,
				'updated_at' => $date
			],
			[
				'name' => 'Rating',
				'type' => 'rating',
				'template' => 'rating',
				'created_at' => $date,
				'updated_at' => $date
			]
		]);
	}
}
