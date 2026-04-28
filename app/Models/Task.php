<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'class_id',
        'research_group_id',
        'title',
        'description',
        'due_date',
        'file_path',
        'max_submissions',
        'allow_late_submission',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_submissions' => 'integer',
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
