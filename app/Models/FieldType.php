<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FieldType extends Model
{
	public function fields(): BelongsToMany
	{
		return $this->belongsToMany(FormField::class);
	}
}
