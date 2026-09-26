<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property Carbon $started_at
 * @property Carbon $ended_at
 * @property string $status
 * 
 * @property Lesson $lesson
 * @property User $user
 * @property Collection|Message[] $messages
 *
 * @package App\Models
 */
class Conversation extends Model
{
	protected $table = 'conversations';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'lesson_id' => 'int',
		'started_at' => 'datetime',
		'ended_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'lesson_id',
		'started_at',
		'ended_at',
		'status'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function messages()
	{
		return $this->hasMany(Message::class);
	}
}
