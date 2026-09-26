<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * 
 * @property int $id
 * @property int|null $conversation_id
 * @property string $role
 * @property string $content
 * @property Carbon $created_at
 * 
 * @property Conversation|null $conversation
 *
 * @package App\Models
 */
class Message extends Model
{
	protected $table = 'messages';
	public $timestamps = false;

	protected $casts = [
		'conversation_id' => 'int'
	];

	protected $fillable = [
		'conversation_id',
		'role',
		'content'
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class);
	}
}
