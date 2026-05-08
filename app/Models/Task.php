<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $table = 'tasks';
    protected $fillable = [
        'class_id',
        'research_group_id',
        'title',
        'description',
        'due_date',
        'file_path',
        'max_submissions',
        'max_points',
        'allow_late_submission',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_submissions' => 'integer',
        'max_points' => 'integer',
        'allow_late_submission' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function researchGroup()
    {
        return $this->belongsTo(ResearchGroup::class, 'research_group_id', 'Group_ID');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
