<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $fillable = ['userID', 'SFname', 'SMname', 'SLname', 'program_id', 'Group_ID', 'status'];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function researchGroup()
    {
        return $this->belongsTo(ResearchGroup::class, 'Group_ID');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}

