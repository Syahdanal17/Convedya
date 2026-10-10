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
 * @property Carbon $updated_at
 *
 * @property Language $language
 * @property Stage|null $current_stage
 * @property User $user
 *
 * @package App\Models
 */
class UserLanguage extends Model
{
	protected $table = 'user_languages';

	protected $casts = [
		'user_id' => 'int',
		'language_id' => 'int',
		'current_stage_id' => 'int',
		'progress' => 'float',
		'updated_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'language_id',
		'current_stage_id',
		'progress',
		'updated_at'
	];

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	public function current_stage()
	{
		return $this->belongsTo(Stage::class, 'current_stage_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
