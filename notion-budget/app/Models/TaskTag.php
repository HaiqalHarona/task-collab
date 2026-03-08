<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskTag
 * 
 * @property int $task_id
 * @property int $tag_id
 * 
 * @property Task $task
 * @property Tag $tag
 *
 * @package App\Models
 */
class TaskTag extends Model
{
	protected $table = 'task_tags';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'task_id',
		'tag_id'
	];

	protected $casts = [
		'task_id' => 'int',
		'tag_id' => 'int'
	];

	public function task()
	{
		return $this->belongsTo(Task::class);
	}

	public function tag()
	{
		return $this->belongsTo(Tag::class);
	}
}
