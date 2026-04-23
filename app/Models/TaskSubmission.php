<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $table = 'task_submissions';
    protected $fillable = [
        'task_id',
        'student_id',
        'submission_text',
        'file_path',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function comments()
    {
        return $this->hasMany(SubmissionComment::class, 'submission_id');
    }
}

