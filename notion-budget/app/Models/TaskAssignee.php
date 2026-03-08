<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskAssignee
 * 
 * @property int $task_id
 * @property string $user_email
 * 
 * @property Task $task
 * @property User $user
 *
 * @package App\Models
 */
class TaskAssignee extends Model
{
	protected $table = 'task_assignees';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'task_id',
		'user_email'
	];

	protected $casts = [
		'task_id' => 'int'
	];

	public function task()
	{
		return $this->belongsTo(Task::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_email', 'email');
	}
}
