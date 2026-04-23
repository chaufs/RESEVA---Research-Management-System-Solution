<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teachers;
use App\Models\Student;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.users');
    }

    public function createTeacher()
    {
        $departments = Department::all();
        return view('user-management.create-teacher', compact('departments'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'firstname' => 'required|string|max:255',
            'Middlename' => 'nullable|string|max:255',
            'Lastname' => 'required|string|max:255',
            'department_id' => 'required|exists:department,department_id',
            'specialization' => 'nullable|string|max:255',
            'qualification' => 'nullable|in:Bachelor,Master,PhD',
            'status' => 'required|in:active,inactive',
        ]);

        $name = $request->firstname . ' ' . ($request->Middlename ? $request->Middlename . ' ' : '') . $request->Lastname;

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Teachers::create([
            'userID' => $user->userID,
            'firstname' => $request->firstname,
            'Middlename' => $request->Middlename,
            'Lastname' => $request->Lastname,
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'qualification' => $request->qualification,
            'status' => $request->status,
        ]);

        return redirect()->route('user-management.index')->with('success', 'Teacher created successfully.');
    }

    public function createStudent()
    {
        $programs = Program::all();
        return view('user-management.create-student', compact('programs'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'SFname' => 'required|string|max:255',
            'SMname' => 'nullable|string|max:255',
            'SLname' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,program_id',
            'status' => 'required|in:active,inactive',
        ]);

        $name = $request->SFname . ' ' . ($request->SMname ? $request->SMname . ' ' : '') . $request->SLname;

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Student::create([
            'userID' => $user->userID,
            'SFname' => $request->SFname,
            'SMname' => $request->SMname,
            'SLname' => $request->SLname,
            'program_id' => $request->program_id,
            'status' => $request->status,
        ]);

        return redirect()->route('user-management.index')->with('success', 'Student created successfully.');
    }

    public function toggleStatus(Request $request, $role, $id)
    {
        if (! in_array($role, ['teacher', 'student'], true)) {
            abort(404);
        }

        if ($role === 'teacher') {
            $record = Teachers::findOrFail($id);
        } else {
            $record = Student::findOrFail($id);
        }

        $record->status = $record->status === 'active' ? 'inactive' : 'active';
        $record->save();

        return redirect()->route('admin.users')->with('success', ucfirst($role) . ' status updated successfully.');
    }
}
