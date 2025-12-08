<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Helpers\ClassroomHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClassroomController extends Controller
{
    /**
     * Handle joining a classroom with access code
     */
    public function join(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'access_code' => ['required', 'string', 'size:6'],
        ], [
            'access_code.required' => 'Please enter the classroom access code',
            'access_code.size' => 'Access code must be 6 characters',
        ]);

        // Find classroom by access code
        $classroom = ClassroomHelper::findByAccessCode($validated['access_code']);

        if (!$classroom) {
            return back()->withErrors(['access_code' => 'Invalid classroom code. Please check and try again.']);
        }

        // Check if already enrolled
        if ($classroom->students()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('classrooms.show', $classroom)->with('info', 'You are already enrolled in this classroom.');
        }

        // Add student to classroom
        $classroom->students()->attach(auth()->id());

        return redirect()->route('dashboard')->with('success', 'Successfully joined ' . $classroom->name . '!');
    }

    /**
     * Show a single classroom
     */
    public function show(Classroom $classroom): View
    {
        // Check if user is the teacher or a student of this classroom
        $isTeacher = $classroom->teacher_id === auth()->id();
        $isStudent = $classroom->students()->where('user_id', auth()->id())->exists();
        
        if (!$isTeacher && !$isStudent) {
            abort(403, 'Unauthorized');
        }

        $students = $classroom->students()->get();

        return view('classrooms.show', compact('classroom', 'students'));
    }

    /**
     * Show all classrooms for the authenticated user
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get classrooms where user is teacher
        $ownedClassrooms = Classroom::where('teacher_id', $user->id)->get();
        
        // Get classrooms where user is student
        $enrolledClassrooms = $user->enrolledClassrooms()->get();

        return view('classrooms.index', compact('ownedClassrooms', 'enrolledClassrooms'));
    }

    /**
     * Create a new classroom (teacher only)
     */
    public function create(): View
    {
        return view('classrooms.create');
    }

    /**
     * Store a new classroom
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('classroom-covers', 'public');
            $validated['cover_image'] = $path;
        }

        $classroom = Classroom::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'cover_image' => $validated['cover_image'] ?? null,
            'teacher_id' => auth()->id(),
        ]);

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Classroom created successfully!');
    }

    /**
     * Edit a classroom
     */
    public function edit(Classroom $classroom): View
    {
        // Check if user is the teacher
        if ($classroom->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('classrooms.edit', compact('classroom'));
    }

    /**
     * Update a classroom
     */
    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        // Check if user is the teacher
        if ($classroom->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($classroom->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($classroom->cover_image);
            }
            $path = $request->file('cover_image')->store('classroom-covers', 'public');
            $validated['cover_image'] = $path;
        }

        $classroom->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'cover_image' => $validated['cover_image'] ?? $classroom->cover_image,
        ]);

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Classroom updated successfully!');
    }

    /**
     * Delete a classroom
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        // Check if user is the teacher
        if ($classroom->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete cover image if exists
        if ($classroom->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($classroom->cover_image);
        }

        $classroom->delete();

        return redirect()->route('dashboard')->with('success', 'Classroom deleted successfully!');
    }
}
