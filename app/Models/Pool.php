<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pool
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string|null $color
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Project $project
 * @property Collection|Task[] $tasks
 */
class Pool extends Model
{
    protected $table = 'pools';

    protected $casts = [
        'project_id' => 'int',
        'position' => 'int',
    ];

    protected $fillable = [
        'project_id',
        'name',
        'color',
        'position',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }
}
