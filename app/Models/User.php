<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
 * @property Collection|UserLanguage[] $user_languages
 * @property Collection|UserProgress[] $user_progresses
 *
 * @package App\Models
 */
class User extends Authenticatable
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
		'remember_token',
		'profile_picture_url',
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
					->withPivot('id', 'current_stage_id', 'progress', 'updated_at');
	}

	public function user_languages()
	{
		return $this->hasMany(UserLanguage::class);
	}

	public function user_progresses()
	{
		return $this->hasMany(UserProgress::class);
	}

	protected function avatar(): Attribute
	{
		return Attribute::get(function () {
			$path = $this->profile_picture_url;

			if (! $path) {
				return null;
			}

			if (Str::startsWith($path, ['http://', 'https://'])) {
				return $path;
			}

			return '/storage/' . ltrim($path, '/');
		});
	}
}
