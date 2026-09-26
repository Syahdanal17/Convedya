<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lesson
 * 
 * @property int $id
 * @property int $stage_id
 * @property int $user_id
 * @property string|null $title
 * @property string $summary
 * @property string $objective
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Stage $stage
 * @property User $user
 * @property Collection|Conversation[] $conversations
 * @property Collection|LessonStep[] $lesson_steps
 *
 * @package App\Models
 */
class Lesson extends Model
{
	protected $table = 'lessons';

	protected $casts = [
		'stage_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'stage_id',
		'user_id',
		'title',
		'summary',
		'objective',
		'status'
	];

	public function stage()
	{
		return $this->belongsTo(Stage::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function conversations()
	{
		return $this->hasMany(Conversation::class);
	}

	public function lesson_steps()
	{
		return $this->hasMany(LessonStep::class);
	}
}
