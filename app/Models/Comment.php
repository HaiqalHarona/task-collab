<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $task_id
 * @property string $user_email
 * @property string $body
 * @property Carbon|null $created_at
 * 
 * @property Task $task
 * @property User $user
 *
 * @package App\Models
 */
class Comment extends Model
{
	protected $table = 'comments';
	public $timestamps = false;

	protected $casts = [
		'task_id' => 'int'
	];

	protected $fillable = [
		'task_id',
		'user_email',
		'body'
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
