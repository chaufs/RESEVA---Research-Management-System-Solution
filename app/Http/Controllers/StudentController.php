<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student;
        if (!$student || $student->status !== 'active') {
            abort(403, 'Student account inactive.');
        }

        $classes = $student->classes()->with('tasks')->get();

        $recentTasks = Task::whereHas('class.students', fn($q) => $q->where('student_id', $student->student_id))
            ->latest('due_date')
            ->with('class')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'classes', 'recentTasks'));
    }

    public function classes()
    {
        $student = Auth::user()->student;
        $classes = $student->classes()->withCount('tasks')->with('teacher')->get();

        return view('student.classes.index', compact('classes'));
    }

    public function class(Classes $class)
    {
        $student = Auth::user()->student;
        if (!$student->classes->contains($class)) {
            abort(403);
        }

        $class->load(['tasks.researchGroup', 'teacher']);

        // Group tasks by research group
        $tasksByGroup = $class->tasks->groupBy('research_group_id');

        return view('student.classes.show', compact('class', 'tasksByGroup'));
    }

    public function task(Task $task)
    {
        $student = Auth::user()->student;
        if (!$student->classes->contains($task->class)) {
            abort(403);
        }

        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->student_id)
            ->with('comments.teacher')
            ->first();

        return view('student.tasks.show', compact('task', 'student', 'submission'));
    }

    public function submitTask(Request $request, Task $task)
    {
        $student = Auth::user()->student;
        if (!$student->classes->contains($task->class)) {
            abort(403);
        }

        $request->validate([
            'submission_text' => 'nullable|string|max:5000',
            'submission_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,png'
        ]);

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->storePublicly('submissions', 'public');
        }

        TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => $student->student_id],
            [
                'submission_text' => $request->submission_text,
                'file_path' => $filePath,
                'submitted_at' => now()
            ]
        );

        return redirect()->route('student.task', $task)->with('success', 'Submission updated successfully.');
    }
}

