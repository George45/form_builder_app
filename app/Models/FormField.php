<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FormField extends Model
{
	public function form(): HasOne
	{
		return $this->hasOne(Form::class);
	}

	public function field(): HasOne
	{
		return $this->hasOne(FieldType::class);
	}
}
