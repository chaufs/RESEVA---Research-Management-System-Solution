<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use App\Models\ResearchGroup;
use App\Models\Student;
use App\Models\Teachers;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddMoreTeachersAndStudentsSeeder extends Seeder
{
    /**
     * Run the seeder to add 12 teachers and 30 students.
     */
    public function run(): void
    {
        // Get existing departments and programs
        $departments = Department::all();
        $programs = Program::all();
        $groups = ResearchGroup::all();

        if ($departments->isEmpty() || $programs->isEmpty()) {
            $this->command->error('Departments and Programs must be seeded first!');
            return;
        }

        // Teacher first names
        $teacherFirstNames = [
            'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Jennifer',
            'James', 'Lisa', 'William', 'Amanda', 'Richard', 'Patricia'
        ];

        $teacherLastNames = [
            'Johnson', 'Smith', 'Williams', 'Brown', 'Jones', 'Garcia',
            'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Anderson', 'Taylor'
        ];

        $specializations = [
            'Software Engineering', 'Data Science', 'Web Development',
            'Cloud Computing', 'Artificial Intelligence', 'Network Security',
            'Mobile Development', 'Database Design', 'DevOps', 'Machine Learning',
            'Cybersecurity', 'Systems Architecture'
        ];

        // Add 12 Teachers
        $teachers = [];
        for ($i = 1; $i <= 12; $i++) {
            $firstName = $teacherFirstNames[$i - 1];
            $lastName = $teacherLastNames[$i - 1];
            $email = strtolower($firstName . '.' . $lastName . '@reseva.test');

            $user = User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            $teacher = Teachers::create([
                'userID' => $user->userID,
                'firstname' => $firstName,
                'Middlename' => substr($firstName, 0, 1),
                'Lastname' => $lastName,
                'department_id' => $departments->random()->department_id,
                'specialization' => $specializations[$i - 1],
                'qualification' => collect(['Master', 'PhD', 'Bachelor'])->random(),
                'status' => 'active',
            ]);

            $teachers[] = $teacher;
        }

        // Student first names and last names
        $studentFirstNames = [
            'Alex', 'Bailey', 'Casey', 'Dakota', 'Evan', 'Fiona', 'Gregory', 'Hannah',
            'Isaac', 'Julia', 'Kevin', 'Laura', 'Mason', 'Natalie', 'Oliver', 'Piper',
            'Quinn', 'Rachel', 'Samuel', 'Taylor', 'Ulysses', 'Violet', 'William', 'Xavier',
            'Yara', 'Zachary', 'Adrian', 'Bella', 'Cameron', 'Diana'
        ];

        $studentLastNames = [
            'Turner', 'Phillips', 'Campbell', 'Parker', 'Evans', 'Edwards', 'Collins', 'Reeves',
            'Grant', 'Hunter', 'Brennan', 'Bishop', 'Leonard', 'Carter', 'Harper', 'Powell',
            'Long', 'Patterson', 'Hughes', 'Flores', 'Washington', 'Butler', 'Simmons', 'Bryant',
            'Alexander', 'Russell', 'Griffin', 'Hayes', 'Hicks', 'Crawford'
        ];

        // Add 30 Students
        for ($i = 1; $i <= 30; $i++) {
            $firstName = $studentFirstNames[$i - 1];
            $lastName = $studentLastNames[$i - 1];
            $email = strtolower($firstName . '.' . $lastName . '@reseva.test');

            $user = User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            $student = Student::create([
                'userID' => $user->userID,
                'SFname' => $firstName,
                'SMname' => substr($firstName, 0, 1),
                'SLname' => $lastName,
                'program_id' => $programs->random()->program_id,
                'Group_ID' => $groups->isNotEmpty() ? $groups->random()->Group_ID : null,
                'status' => 'active',
            ]);
        }

        $this->command->info('Successfully added 12 teachers and 30 students!');
    }
}
