<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    protected $fillable = ['program_name', 'department_id'];
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'program_id');
    }

    public function researchGroups()
    {
        return $this->hasMany(ResearchGroup::class, 'program_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'program_id');
    }
}
