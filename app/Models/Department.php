<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    protected $fillable = ['department_name'];
    public $timestamps = false;

    public function programs()
    {
        return $this->hasMany(Program::class, 'department_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teachers::class, 'department_id');
    }
}
