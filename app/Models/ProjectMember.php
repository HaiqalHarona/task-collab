<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectMember
 *
 * @property int $id
 * @property int $project_id
 * @property string $user_email
 * @property string $role  (owner|admin|member|viewer)
 * @property Carbon|null $added_at
 *
 * @property Project $project
 * @property User $user
 */
class ProjectMember extends Model
{
    protected $table = 'project_members';
    public $timestamps = false;

    protected $casts = [
        'project_id' => 'int',
        'added_at' => 'datetime',
    ];

    protected $fillable = [
        'project_id',
        'user_email',
        'role',
        'added_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
