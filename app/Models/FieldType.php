<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FieldType extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'type',
		'template'
	];

	public function fields(): BelongsToMany
	{
		return $this->belongsToMany(FormField::class);
	}
}
