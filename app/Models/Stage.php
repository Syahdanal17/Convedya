<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stage
 * 
 * @property int $id
 * @property int $language_id
 * @property string $name
 * @property string $description
 * @property string $level
 * @property int $order
 * 
 * @property Language $language
 * @property Collection|Lesson[] $lessons
 *
 * @package App\Models
 */
class Stage extends Model
{
	protected $table = 'stages';
	public $timestamps = false;

	protected $casts = [
		'language_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'language_id',
		'name',
		'description',
		'level',
		'order'
	];

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	public function lessons()
	{
		return $this->hasMany(Lesson::class);
	}
}
