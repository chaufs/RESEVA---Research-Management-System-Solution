<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Program;
use App\Models\ResearchGroup;
use App\Models\Student;
use App\Models\Teachers;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\SubmissionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ClassAssignment extends Controller
{
    private function teacherOrAbort(): \App\Models\Teachers
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized.');
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            abort(403, 'Access denied. No teacher profile found.');
        }

        return $teacher;
    }

    public function teacherIndex()
    {
        $teacher = $this->teacherOrAbort();

        $classes = Classes::with(['program', 'teacher', 'students.researchGroup'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->orderBy('class_name')->paginate(15);

        $groups = ResearchGroup::with('program')->orderBy('Group_Name')->get();

        return view('Posts.Teacher.TeacherClassMan', compact('classes', 'groups'));
    }

    public function showClassDetails(Classes $class)
    {
        $teacher = $this->teacherOrAbort();
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Access denied. This is not your class.');
        }

        $class->load(['program', 'teacher', 'students.researchGroup']);
        $class->load(['tasks' => function($query) {
            $query->with(['submissions.student.program', 'submissions.comments.teacher']);
        }]); // Load tasks with submissions and related data

        $groups = ResearchGroup::where('program_id', $class->program_id)->with('students')->orderBy('Group_Name')->get();

        $assignedGroups = $class->students
            ->loadMissing('researchGroup')
            ->pluck('researchGroup')
            ->filter()
            ->unique('Group_ID')
            ->values();

        $selectedGroupId = request('selected_group');
        $selectedGroup = $groups->firstWhere('Group_ID', $selectedGroupId);

        $ungroupedStudents = $class->students
            ->filter(fn($student) => ! $student->researchGroup)
            ->sortBy(fn($student) => trim($student->SLname ?? $student->SFname));

        $groupedStudents = $class->students
            ->filter(fn($student) => $student->researchGroup)
            ->sortBy(fn($student) => [$student->researchGroup->Group_ID ?? 0, trim($student->SLname ?? $student->SFname)]);

        return view('Posts.Teacher.ClassDetails', compact('class', 'groups', 'assignedGroups', 'selectedGroup', 'ungroupedStudents', 'groupedStudents'));
    }

    public function assignGroup(Request $request, Classes $class)
    {
        if (! Schema::hasTable('class_student') || ! Schema::hasTable('students') || ! Schema::hasTable('ResearchGroups')) {
            return redirect()->route('teacher.classes.index')
                ->with('error', 'Group assignment requires the class_student, students, and ResearchGroups tables.');
        }

        $data = $request->validate([
            'group_id' => ['required', 'exists:ResearchGroups,Group_ID'],
        ]);

        $group = ResearchGroup::with('students')->findOrFail($data['group_id']);

        if ($group->students->isEmpty()) {
            return redirect()->route('teacher.classes.index')
                ->with('error', 'The selected group has no students to assign.');
        }

        $class->students()->syncWithoutDetaching($group->students->pluck('student_id')->all());

        return redirect()->route('teacher.classes.show', $class)
            ->with('success', 'Group assigned to class successfully.');
    }

    public function groupStudents(Request $request, Classes $class)
    {
        if (! Schema::hasTable('class_student') || ! Schema::hasTable('students') || ! Schema::hasTable('ResearchGroups')) {
            return redirect()->route('teacher.classes.index')
                ->with('error', 'Student grouping requires the class_student, students, and ResearchGroups tables.');
        }

        $data = $request->validate([
            'group_id' => ['required', 'exists:ResearchGroups,Group_ID'],
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:students,student_id'],
        ]);

        $classStudentIds = $class->students()->pluck('students.student_id')->all();
        $selectedStudentIds = array_values(array_intersect($classStudentIds, $data['student_ids']));

        if (empty($selectedStudentIds)) {
            return redirect()->route('teacher.classes.show', $class)
                ->with('error', 'No selected students belong to this class.');
        }

        Student::whereIn('student_id', $selectedStudentIds)
            ->update(['Group_ID' => $data['group_id']]);

        return redirect()->route('teacher.classes.show', $class)
            ->with('success', 'Selected students have been grouped successfully.');
    }

    public function createGroup(Request $request, Classes $class)
    {
        if (! Schema::hasTable('ResearchGroups')) {
            return redirect()->route('teacher.classes.show', $class)
                ->with('error', 'Creating groups requires the ResearchGroups table.');
        }

        $currentCount = ResearchGroup::where('program_id', $class->program_id)->count();
        $groupNumber = $currentCount + 1;

        $group = ResearchGroup::create([
            'program_id' => $class->program_id,
            'Group_Name' => 'Group ' . $groupNumber,
        ]);

        return redirect()->route('teacher.classes.show', ['class' => $class, 'selected_group' => $group->Group_ID])
            ->with('success', 'Group created successfully.');
    }

    public function assignGroupTask(Request $request, Classes $class, ResearchGroup $group)
    {
        $validated = $request->validate([
            'task_title' => ['required', 'string', 'max:255'],
            'task_description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'task_file' => ['nullable', 'file', 'max:10240'],
            'max_submissions' => ['required', 'integer', 'min:1', 'max:100'],
            'max_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'allow_late_submission' => ['nullable', 'boolean'],
        ]);

        $filePath = null;
        if ($request->hasFile('task_file')) {
            $filePath = $request->file('task_file')->storePublicly('group-tasks', 'public');
        }

        Task::create([
            'class_id' => $class->id,
            'research_group_id' => $group->Group_ID,
            'title' => $validated['task_title'],
            'description' => $validated['task_description'],
            'due_date' => $validated['due_date'],
            'file_path' => $filePath,
            'max_submissions' => $validated['max_submissions'],
            'max_points' => $validated['max_points'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
        ]);

        return redirect()->route('teacher.classes.show', ['class' => $class, 'selected_group' => $group->Group_ID])
            ->with('success', 'Task assigned to ' . $group->Group_Name . ' successfully.');
    }

    public function updateTask(Request $request, Task $task)
    {
        $teacher = $this->teacherOrAbort();
        if ($task->class?->teacher_id !== $teacher->id) {
            abort(403, 'Access denied. This task does not belong to your class.');
        }

        $validated = $request->validate([
            'task_title' => ['required', 'string', 'max:255'],
            'task_description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'task_file' => ['nullable', 'file', 'max:10240'],
            'max_submissions' => ['required', 'integer', 'min:1', 'max:100'],
            'max_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'allow_late_submission' => ['nullable', 'boolean'],
        ]);

        $filePath = $task->file_path;

        if ($request->hasFile('task_file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }

            $filePath = $request->file('task_file')->storePublicly('group-tasks', 'public');
        }

        $task->update([
            'title' => $validated['task_title'],
            'description' => $validated['task_description'],
            'due_date' => $validated['due_date'],
            'file_path' => $filePath,
            'max_submissions' => $validated['max_submissions'],
            'max_points' => $validated['max_points'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
        ]);

        return redirect()->route('teacher.classes.show', [
            'class' => $task->class_id,
            'selected_group' => $task->research_group_id,
        ])->with('success', 'Task updated successfully.');
    }

    public function deleteTask(Task $task)
    {
        $teacher = $this->teacherOrAbort();
        if ($task->class?->teacher_id !== $teacher->id) {
            abort(403, 'Access denied. This task does not belong to your class.');
        }

        $task->delete();

        return redirect()->route('teacher.classes.show', [
            'class' => $task->class_id,
            'selected_group' => $task->research_group_id,
        ])->with('success', 'Task deleted successfully.');
    }

    public function teacherDashboard()
    {
        $teacher = $this->teacherOrAbort();

$classes = Classes::with(['program', 'students'])
            ->where('teacher_id', $teacher->id)
            ->get();
        
        // Skip tasks loading to avoid column error; calculate totalTasks differently
        $totalTasks = Task::whereHas('class.teacher', fn($q) => $q->where('id', $teacher->id))->count();

        $totalClasses = $classes->count();
        $totalStudents = $classes->sum(fn($class) => $class->students->count());
// $totalTasks already calculated above
        $pendingSubmissions = TaskSubmission::whereHas('task', function($q) use ($teacher) {
            $q->whereHas('class.teacher', fn($q2) => $q2->where('id', $teacher->id));
        })->count();

        $classesByProgram = $classes->groupBy(fn($class) => $class->program?->program_name ?? 'No Program');

        return view('Posts.Teacher.teacherdash', compact('classes', 'totalClasses', 'totalStudents', 'totalTasks', 'pendingSubmissions', 'classesByProgram'));
    }

    public function index()
    {
        $classes = Classes::with(['program', 'teacher', 'students'])->paginate(15);
        // Exclude admin user (identified by specific email) from teachers list
        $teachers = Teachers::where('status', 'active')
            ->whereHas('user', fn($q) => $q->where('email', '!=', 'admin@reseva.test'))
            ->orderBy('Lastname')
            ->get();
        $students = Student::orderBy('SLname')->get();
        $programs = Program::orderBy('program_name')->get();

        return view('Posts.Admin.Adminclass', compact('classes', 'teachers', 'students', 'programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:class,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,student_id'],
        ]);

        $class = Classes::findOrFail($data['class_id']);
        $class->teacher_id = $data['teacher_id'];
        $class->save();

        if (! empty($data['student_ids'])) {
            $class->students()->sync($data['student_ids']);
        } else {
            $class->students()->detach();
        }

        return redirect()->route('adminclass.index')
            ->with('success', 'Teacher and students assigned successfully.');
    }

    public function storeClass(Request $request)
    {
        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'program_id' => ['required', 'exists:programs,program_id'],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive,archived'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,student_id'],
        ]);

        $class = Classes::create([
            'class_name' => $data['class_name'],
            'subject' => $data['subject'],
            'program_id' => $data['program_id'],
            'year_level' => $data['year_level'],
            'teacher_id' => $data['teacher_id'],
            'max_capacity' => $data['max_capacity'],
            'status' => $data['status'],
        ]);

        if (! empty($data['student_ids'])) {
            $class->students()->sync($data['student_ids']);
        }

        return redirect()->route('adminclass.index')
            ->with('success', 'Class created and students assigned successfully.');
    }

    public function toggleClassStatus(Classes $class, $status = null)
    {
        // Handle GET request with status as route parameter
        if ($status && $class) {
            if (!in_array($status, ['active', 'inactive', 'archived'])) {
                return redirect()->route('adminclass.index')
                    ->with('error', 'Invalid status.');
            }

            $class->status = $status;
            $class->save();

            return redirect()->route('adminclass.index')
                ->with('success', 'Class status updated to ' . $status . ' successfully.');
        }

        // Handle POST request
        $request = request();
        $request->validate([
            'status' => 'required|in:active,inactive,archived',
        ]);

        $class->status = $request->status;
        $class->save();

        return redirect()->route('adminclass.index')
            ->with('success', 'Class status updated to ' . $request->status . ' successfully.');
    }

    public function addComment(Request $request, TaskSubmission $submission)
    {
        $teacher = $this->teacherOrAbort();

        // Verify the submission belongs to a task from this teacher's class
        $task = $submission->task;
        if (!$task || $task->class->teacher_id !== $teacher->id) {
            abort(403, 'Access denied. This submission does not belong to your class.');
        }

        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        SubmissionComment::create([
            'submission_id' => $submission->id,
            'teacher_id' => $teacher->id,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function gradeSubmission(Request $request, TaskSubmission $submission)
    {
        $teacher = $this->teacherOrAbort();

        // Verify the submission belongs to a task from this teacher's class
        $task = $submission->task;
        if (!$task || $task->class->teacher_id !== $teacher->id) {
            abort(403, 'Access denied. This submission does not belong to your class.');
        }

        $maxGrade = $submission->task->max_points ?? 100;
        $request->validate([
            'grade' => 'required|numeric|min:0|max:' . $maxGrade,
        ]);

        $submission->update([
            'grade' => $request->grade,
        ]);

        return redirect()->back()->with('success', 'Grade assigned successfully.');
    }

    public function dashboard()
    {
        $totalUsers = \App\Models\User::count();
        $totalTeachers = \App\Models\Teachers::count();
        $totalStudents = \App\Models\Student::count();
        $totalClasses = Classes::count();

        $classesByStatus = Classes::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $classesWithStudents = Classes::withCount('students')
            ->orderByDesc('students_count')
            ->limit(8)
            ->get();

        $studentCountsByYear = DB::table('class')
            ->join('class_student', 'class.id', '=', 'class_student.class_id')
            ->select(DB::raw("CASE
                WHEN year_level = 1 THEN '1st Year'
                WHEN year_level = 2 THEN '2nd Year'
                WHEN year_level = 3 THEN '3rd Year'
                WHEN year_level = 4 THEN '4th Year'
                ELSE 'Extended'
            END AS year_level_label"), DB::raw('count(distinct class_student.student_id) as total_students'))
            ->groupBy('year_level_label')
            ->orderByRaw("FIELD(year_level_label, '1st Year', '2nd Year', '3rd Year', '4th Year', 'Extended')")
            ->pluck('total_students', 'year_level_label')
            ->toArray();

        return view('Posts.Admin.AdminDashboard', compact(
            'totalUsers',
            'totalTeachers',
            'totalStudents',
            'totalClasses',
            'classesByStatus',
            'classesWithStudents',
            'studentCountsByYear'
        ));
    }

    public function adminUsers()
    {
        $teachers = Teachers::with('user')
            ->whereHas('user', fn($q) => $q->where('email', '!=', 'admin@reseva.test'))
            ->orderBy('Lastname')
            ->paginate(15, ['*'], 'teachers_page');
        $students = Student::with('user')->orderBy('SLname')->paginate(15, ['*'], 'students_page');

        return view('Posts.Admin.UserMan', compact('teachers', 'students'));
    }
}
