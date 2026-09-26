<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserLanguage
 * 
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property int $current_stage_id
 * @property float $progress
 * @property Carbon $created_at
 * @property Carbon $update_at
 * 
 * @property Language $language
 * @property User $user
 *
 * @package App\Models
 */
class UserLanguage extends Model
{
	protected $table = 'user_languages';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'language_id' => 'int',
		'current_stage_id' => 'int',
		'progress' => 'float',
		'update_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'language_id',
		'current_stage_id',
		'progress',
		'update_at'
	];

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
