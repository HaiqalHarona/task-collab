<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string|null $color
 *
 * @property Project $project
 */
class Tag extends Model
{
	protected $table = 'tags';
	public $timestamps = false;

	protected $casts = [
		'project_id' => 'int',
	];

	protected $fillable = [
		'project_id',
		'name',
		'color',
	];

	public function project()
	{
		return $this->belongsTo(Project::class);
	}
}
