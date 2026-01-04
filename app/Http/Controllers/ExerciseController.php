<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only therapists can manage exercises
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $query = Exercise::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('instructions', 'like', '%' . $request->search . '%');
            });
        }

        $exercises = $query->latest()->paginate(15);

        return view('exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return view('exercises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'equipment_needed' => 'nullable|string',
            'safety_warnings' => 'nullable|string',
            'step_by_step_guide' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exercises', 'public');
            $validated['image_path'] = $imagePath;
        }

        Exercise::create($validated);

        return redirect()->route('exercises.index')->with('success', 'Exercise created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        return view('exercises.show', compact('exercise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return view('exercises.edit', compact('exercise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exercise $exercise)
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'equipment_needed' => 'nullable|string',
            'safety_warnings' => 'nullable|string',
            'step_by_step_guide' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($exercise->image_path) {
                \Storage::disk('public')->delete($exercise->image_path);
            }
            $imagePath = $request->file('image')->store('exercises', 'public');
            $validated['image_path'] = $imagePath;
        }

        $exercise->update($validated);

        return redirect()->route('exercises.index')->with('success', 'Exercise updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $exerciseName = $exercise->name;
        $exercise->delete();

        return redirect()->route('exercises.index')->with('success', "Exercise \"$exerciseName\" deleted successfully!");
    }
}
