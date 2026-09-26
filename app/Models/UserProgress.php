<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProgress
 * 
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property int $xp
 * @property int $completed_lessons
 * @property int $streak
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Language $language
 * @property User $user
 *
 * @package App\Models
 */
class UserProgress extends Model
{
	protected $table = 'user_progress';

	protected $casts = [
		'user_id' => 'int',
		'language_id' => 'int',
		'xp' => 'int',
		'completed_lessons' => 'int',
		'streak' => 'int'
	];

	protected $fillable = [
		'user_id',
		'language_id',
		'xp',
		'completed_lessons',
		'streak'
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
