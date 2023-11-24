<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FormField extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'form_id',
		'field_type',
		'name',
		'description',
		'config',
		'required'
	];

	public function form(): BelongsTo
	{
		return $this->belongsTo(Form::class);
	}

	public function field(): HasOne
	{
		return $this->hasOne(FieldType::class);
	}
}
