<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'class';
    protected $fillable = ['class_name', 'subject', 'program_id', 'year_level', 'teacher_id', 'max_capacity', 'status'];

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id');
    }

    public function getAssignedGroupsAttribute()
    {
        return $this->students
            ->loadMissing('researchGroup')
            ->pluck('researchGroup')
            ->filter()
            ->unique('Group_ID')
            ->values();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'class_id', 'id');
    }
}

