<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAchievement
 * 
 * @property int $user_id
 * @property int $achievement_id
 * @property Carbon $unlocked_at
 * 
 * @property Achievement $achievement
 * @property User $user
 *
 * @package App\Models
 */
class UserAchievement extends Model
{
	protected $table = 'user_achievements';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'achievement_id' => 'int',
		'unlocked_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'achievement_id',
		'unlocked_at'
	];

	public function achievement()
	{
		return $this->belongsTo(Achievement::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
