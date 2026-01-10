<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'therapist') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $query = Exercise::query();
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('instructions', 'like', '%' . $request->search . '%');
            });
        }

        $exercises = $query->latest()->paginate(15);
        return view('exercises.index', compact('exercises'));
    }

    public function create()
    {
        return view('exercises.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'required|string',
            'image_url' => 'nullable|url',
            'video_url' => 'nullable|url',
        ]);

        Exercise::create($validated);

        return redirect()->route('exercises.index')->with('success', 'Exercise created successfully!');
    }

    public function show(Exercise $exercise)
    {
        return view('exercises.show', compact('exercise'));
    }

    public function edit(Exercise $exercise)
    {
        return view('exercises.edit', compact('exercise'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'required|string',
            'image_url' => 'nullable|url',
            'video_url' => 'nullable|url',
        ]);

        $exercise->update($validated);

        return redirect()->route('exercises.show', $exercise)->with('success', 'Exercise updated successfully!');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return redirect()->route('exercises.index')->with('success', 'Exercise deleted.');
    }
}