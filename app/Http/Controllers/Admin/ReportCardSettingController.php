<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCardSetting;
use App\Models\SchoolSection;
use Illuminate\Http\Request;

class ReportCardSettingController extends Controller
{
    public function index()
    {
        $settings = ReportCardSetting::with('schoolSection')
            ->orderBy('school_section_id')
            ->get();

        $sections = SchoolSection::orderBy('name')->get();

        return view('admin.report-cards.settings', compact(
            'settings',
            'sections'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => [
                'nullable',
                'integer',
                'exists:school_sections,id',
            ],
            'template_name' => ['required', 'string', 'max:100'],
            'template_key' => [
                'required',
                'in:modern,classic,compact',
            ],
            'show_teacher_comment' => ['nullable', 'boolean'],
            'show_principal_comment' => ['nullable', 'boolean'],
            'show_behaviour' => ['nullable', 'boolean'],
            'show_psychomotor' => ['nullable', 'boolean'],
            'show_affective' => ['nullable', 'boolean'],
            'show_attendance' => ['nullable', 'boolean'],
            'show_class_position' => ['nullable', 'boolean'],
            'show_next_term_date' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $toggles = [
            'show_teacher_comment',
            'show_principal_comment',
            'show_behaviour',
            'show_psychomotor',
            'show_affective',
            'show_attendance',
            'show_class_position',
            'show_next_term_date',
            'is_active',
        ];

        foreach ($toggles as $toggle) {
            $validated[$toggle] = $request->boolean($toggle);
        }

        $sectionId = $validated['school_section_id'] ?? null;

        ReportCardSetting::updateOrCreate(
            ['school_section_id' => $sectionId],
            $validated
        );

        return redirect()
            ->route('report-cards.settings.index')
            ->with('success', 'Report card settings saved successfully.');
    }
}