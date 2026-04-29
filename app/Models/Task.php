<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 *
 * @property int $id
 * @property int $pool_id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Pool $pool
 * @property Collection|Comment[] $comments
 * @property Collection|TaskAssignee[] $task_assignees
 * @property Collection|Tag[] $tags
 */
class Task extends Model
{
	protected $table = 'tasks';

	protected $casts = [
		'pool_id' => 'int',
		'start_date' => 'date',
		'end_date' => 'date',
		'position' => 'int',
	];

	protected $fillable = [
		'pool_id',
		'title',
		'description',
		'start_date',
		'end_date',
		'position',
	];

	public function pool()
	{
		return $this->belongsTo(Pool::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function task_assignees()
	{
		return $this->hasMany(TaskAssignee::class);
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'task_tags');
	}
}
