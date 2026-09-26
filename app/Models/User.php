<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Conversation[] $conversations
 * @property Collection|Lesson[] $lessons
 * @property Collection|Achievement[] $achievements
 * @property Collection|Language[] $languages
 * @property Collection|UserProgress[] $user_progresses
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function conversations()
	{
		return $this->hasMany(Conversation::class);
	}

	public function lessons()
	{
		return $this->hasMany(Lesson::class);
	}

	public function achievements()
	{
		return $this->belongsToMany(Achievement::class, 'user_achievements')
					->withPivot('unlocked_at');
	}

	public function languages()
	{
		return $this->belongsToMany(Language::class, 'user_languages')
					->withPivot('id', 'current_stage_id', 'progress', 'update_at');
	}

	public function user_progresses()
	{
		return $this->hasMany(UserProgress::class);
	}
}
