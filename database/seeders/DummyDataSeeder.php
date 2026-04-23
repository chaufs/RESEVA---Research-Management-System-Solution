<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Department;
use App\Models\Program;
use App\Models\ResearchGroup;
use App\Models\Student;
use App\Models\Teachers;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the dummy data seed.
     */
    public function run(): void
    {
        $computerScience = Department::create(['department_name' => 'Computer Science']);
        $informationSystems = Department::create(['department_name' => 'Information Systems']);
        $business = Department::create(['department_name' => 'Business Administration']);

        $csProgram = Program::create([
            'program_name' => 'BSc Computer Science',
            'department_id' => $computerScience->department_id,
        ]);

        $isProgram = Program::create([
            'program_name' => 'BSc Information Systems',
            'department_id' => $informationSystems->department_id,
        ]);

        $mbaProgram = Program::create([
            'program_name' => 'MBA Research Management',
            'department_id' => $business->department_id,
        ]);

        $groupAlpha = ResearchGroup::create([
            'program_id' => $csProgram->program_id,
            'Group_Name' => 'Alpha Group',
        ]);

        $groupBeta = ResearchGroup::create([
            'program_id' => $isProgram->program_id,
            'Group_Name' => 'Beta Group',
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@reseva.test',
            'password' => Hash::make('password'),
        ]);

        $teacherUser = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher@reseva.test',
            'password' => Hash::make('password'),
        ]);

        $studentOneUser = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@reseva.test',
            'password' => Hash::make('password'),
        ]);

        $studentTwoUser = User::create([
            'name' => 'Mark Wells',
            'email' => 'mark.wells@reseva.test',
            'password' => Hash::make('password'),
        ]);

        $adminTeacher = Teachers::create([
            'userID' => $adminUser->userID,
            'firstname' => 'Admin',
            'Middlename' => null,
            'Lastname' => 'User',
            'department_id' => $computerScience->department_id,
            'specialization' => 'Research Systems',
            'qualification' => 'PhD',
            'status' => 'active',
        ]);

        $teacherOne = Teachers::create([
            'userID' => $teacherUser->userID,
            'firstname' => 'Teacher',
            'Middlename' => 'A',
            'Lastname' => 'One',
            'department_id' => $informationSystems->department_id,
            'specialization' => 'Information Systems',
            'qualification' => 'Master',
            'status' => 'active',
        ]);

        $studentOne = Student::create([
            'userID' => $studentOneUser->userID,
            'SFname' => 'Jane',
            'SMname' => 'A',
            'SLname' => 'Doe',
            'program_id' => $csProgram->program_id,
            'Group_ID' => $groupAlpha->Group_ID,
            'status' => 'active',
        ]);

        $studentTwo = Student::create([
            'userID' => $studentTwoUser->userID,
            'SFname' => 'Mark',
            'SMname' => 'B',
            'SLname' => 'Wells',
            'program_id' => $isProgram->program_id,
            'Group_ID' => $groupBeta->Group_ID,
            'status' => 'active',
        ]);

        $classOne = Classes::create([
            'class_name' => 'Research Design',
            'subject' => 'Research Methods',
            'program_id' => $csProgram->program_id,
            'year_level' => 3,
            'teacher_id' => $adminTeacher->id,
            'max_capacity' => 25,
            'status' => 'active',
        ]);

        $classTwo = Classes::create([
            'class_name' => 'Systems Analysis',
            'subject' => 'Business Systems',
            'program_id' => $isProgram->program_id,
            'year_level' => 2,
            'teacher_id' => $teacherOne->id,
            'max_capacity' => 30,
            'status' => 'active',
        ]);

        $classOne->students()->attach([
            $studentOne->student_id => ['assigned_at' => now(), 'role' => 'member'],
        ]);

        $classTwo->students()->attach([
            $studentTwo->student_id => ['assigned_at' => now(), 'role' => 'member'],
        ]);
    }
}
