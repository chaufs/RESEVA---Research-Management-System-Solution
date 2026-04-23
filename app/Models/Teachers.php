<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['userID', 'firstname', 'Middlename', 'Lastname', 'department_id', 'specialization', 'qualification', 'status'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
