<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LessonStep
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $step_number
 * @property string|null $goal
 * @property bool $required
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Lesson $lesson
 *
 * @package App\Models
 */
class LessonStep extends Model
{
    protected $table = 'lesson_steps';
    public $timestamps = false;

	protected $casts = [
		'lesson_id' => 'int',
		'step_number' => 'int',
		'required' => 'bool',
		'updated_at' => 'datetime'
	];

	protected $fillable = [
        "lesson_id",
        "step_number",
        "goal",
        "description",
        "required",
        "status"
    ];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class);
	}
}
