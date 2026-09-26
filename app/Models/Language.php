<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $icon
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Stage[] $stages
 * @property Collection|User[] $users
 * @property Collection|UserProgress[] $user_progresses
 *
 * @package App\Models
 */
class Language extends Model
{
	protected $table = 'languages';

	protected $casts = [
		'is_active' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'icon',
		'is_active'
	];

	public function stages()
	{
		return $this->hasMany(Stage::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_languages')
					->withPivot('id', 'current_stage_id', 'progress', 'update_at');
	}

	public function user_progresses()
	{
		return $this->hasMany(UserProgress::class);
	}
}
