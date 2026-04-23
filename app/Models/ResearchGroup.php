<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchGroup extends Model
{
    protected $table = 'ResearchGroups';
    protected $primaryKey = 'Group_ID';
    protected $fillable = ['program_id', 'Group_Name'];
    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'Group_ID');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'research_group_id', 'Group_ID');
    }
}

