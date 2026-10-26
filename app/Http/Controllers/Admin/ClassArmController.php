<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassArm;
use App\Models\ClassLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassArmController extends Controller
{
    public function index()
    {
        $classArms = ClassArm::with('classLevel.schoolSection')
            ->orderBy('class_level_id')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.class-arms.index', compact('classArms'));
    }

    public function create()
    {
        $classLevels = ClassLevel::with('schoolSection')
            ->where('is_active', true)
            ->orderBy('school_section_id')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.class-arms.create', compact('classLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_level_id' => [
                'required',
                'exists:class_levels,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_arms', 'name')
                    ->where('class_level_id', $request->class_level_id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        ClassArm::create($validated);

        return redirect()
            ->route('class-arms.index')
            ->with('success', 'Class arm created successfully.');
    }

    public function show(ClassArm $classArm)
    {
        $classArm->load([
            'classLevel.schoolSection',
            'students',
        ]);

        return view('admin.class-arms.show', compact('classArm'));
    }

    public function edit(ClassArm $classArm)
    {
        $classLevels = ClassLevel::with('schoolSection')
            ->where('is_active', true)
            ->orderBy('school_section_id')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.class-arms.edit', compact(
            'classArm',
            'classLevels'
        ));
    }

    public function update(Request $request, ClassArm $classArm)
    {
        $validated = $request->validate([
            'class_level_id' => [
                'required',
                'exists:class_levels,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_arms', 'name')
                    ->where('class_level_id', $request->class_level_id)
                    ->ignore($classArm->id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $classArm->update($validated);

        return redirect()
            ->route('class-arms.index')
            ->with('success', 'Class arm updated successfully.');
    }

    public function destroy(ClassArm $classArm)
    {
        $classArm->delete();

        return redirect()
            ->route('class-arms.index')
            ->with('success', 'Class arm deleted successfully.');
    }
}