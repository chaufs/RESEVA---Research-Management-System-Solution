<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionComment extends Model
{
    protected $table = 'submission_comments';
    protected $fillable = ['submission_id', 'teacher_id', 'comment'];

    public function submission()
    {
        return $this->belongsTo(TaskSubmission::class, 'submission_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id');
    }
}

