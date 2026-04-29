<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
/**
 * Class User
 * 
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property string|null $password
 * @property string|null $remember_token
 * @property Carbon|null $email_verified_at
 * @property string|null $google_id
 * @property string|null $github_id
 * @property string|null $avatar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Comment[] $comments
 * @property Collection|TaskAssignee[] $task_assignees
 * @property Collection|ProjectMember[] $project_members
 * @property Collection|Project[] $projects
 *
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;
	protected $table = 'user';
	protected $fillable = [
		'name',
		'email',
		'password',
		'avatar',
		'google_id',
		'github_id',
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	public function comments()
	{
		return $this->hasMany(Comment::class, 'user_email', 'email');
	}

	public function task_assignees()
	{
		return $this->hasMany(TaskAssignee::class, 'user_email', 'email');
	}

	public function project_members()
	{
		return $this->hasMany(ProjectMember::class, 'user_email', 'email');
	}

	public function projects()
	{
		return $this->hasMany(Project::class, 'owner_email', 'email');
	}
}
