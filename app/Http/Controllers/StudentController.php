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

        $classes = $student->classes()->where('class.status', 'active')->with('tasks')->get();

        $recentTasks = Task::whereHas('class.students', fn($q) => $q->where('students.student_id', $student->student_id))
            ->latest('due_date')
            ->with('class')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'classes', 'recentTasks'));
    }

    public function classes()
    {
        $student = Auth::user()->student;
        $classes = $student->classes()->where('class.status', 'active')->withCount('tasks')->with('teacher')->get();

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

    public function task(Classes $class, Task $task)
    {
        $student = Auth::user()->student;

        if (! $student->classes()->where('class.id', $class->id)->exists()) {
            abort(403);
        }

        if ((int) $task->class_id !== (int) $class->id) {
            abort(404);
        }

        $task->load(['class.teacher', 'researchGroup']);

        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->student_id)
            ->with('comments.teacher')
            ->first();

        $submissionCount = $submission ? $submission->submission_count : 0;

        return view('student.tasks.show', compact('task', 'student', 'submission', 'submissionCount'));
    }

    public function submitTask(Request $request, Task $task)
    {

        $student = Auth::user()->student;
        if (! $student->classes()->where('class.id', $task->class_id)->exists()) {
            abort(403);
        }

        $request->validate([
            'submission_text' => 'nullable|string|max:5000',
            'submission_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,png'
        ]);

        // Check if submission is late
        $isLate = false;
        if ($task->due_date && $task->due_date->isPast()) {
            if ($task->allow_late_submission) {
                $isLate = true;
            } else {
                return redirect()->route('student.classes.task', ['class' => $task->class_id, 'task' => $task->id])
                    ->with('error', 'This task no longer accepts submissions. The due date has passed.');
            }
        }

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->storePublicly('submissions', 'public');
        }

        // Get existing submission count
        $existingSubmission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->student_id)
            ->first();

        $submissionCount = $existingSubmission ? $existingSubmission->submission_count + 1 : 1;

        // Check max submissions limit
        if ($task->max_submissions && $submissionCount > $task->max_submissions) {
            return redirect()->route('student.classes.task', ['class' => $task->class_id, 'task' => $task->id])
                ->with('error', 'You have reached the maximum number of submissions (' . $task->max_submissions . ') for this task.');
        }

        // Check if student belongs to a research group
        $groupId = $student->Group_ID;
        
        if ($groupId) {
            // Group submission: create/update submissions for all group members
            $groupStudents = Student::where('Group_ID', $groupId)->get();
            
            foreach ($groupStudents as $groupStudent) {
                $groupExistingSubmission = TaskSubmission::where('task_id', $task->id)
                    ->where('student_id', $groupStudent->student_id)
                    ->first();
                
                $groupSubmissionCount = $groupExistingSubmission ? $groupExistingSubmission->submission_count + 1 : 1;
                
                TaskSubmission::updateOrCreate(
                    ['task_id' => $task->id, 'student_id' => $groupStudent->student_id],
                    [
                        'submission_text' => $request->submission_text,
                        'file_path' => $filePath,
                        'submitted_at' => now(),
                        'submission_count' => $groupSubmissionCount,
                        'is_late' => $isLate,
                    ]
                );
            }
        } else {
            // Individual submission
            TaskSubmission::updateOrCreate(
                ['task_id' => $task->id, 'student_id' => $student->student_id],
                [
                    'submission_text' => $request->submission_text,
                    'file_path' => $filePath,
                    'submitted_at' => now(),
                    'submission_count' => $submissionCount,
                    'is_late' => $isLate,
                ]
            );
        }

        $message = $groupId 
            ? 'Task submitted successfully for your group.' 
            : 'Submission updated successfully.';

        return redirect()->route('student.classes.task', ['class' => $task->class_id, 'task' => $task->id])->with('success', $message);
    }
}
