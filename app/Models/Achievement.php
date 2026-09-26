<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Achievement
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $icon
 * @property string $requirement
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Achievement extends Model
{
	protected $table = 'achievements';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description',
		'icon',
		'requirement'
	];

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_achievements')
					->withPivot('unlocked_at');
	}
}
