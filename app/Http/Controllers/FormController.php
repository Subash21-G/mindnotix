<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FormSchemaManager;

class FormController extends Controller
{
    // Dashboard: List user forms
    public function index()
    {
        $user = Auth::user();
       $forms = $user->forms()->latest()->get();

        $formCount = $forms->count();

        return view('dashboard', compact('user', 'forms', 'formCount'));
    }

    // Show the form builder for new forms
    public function create()
    {
        return view('forms.builder');
    }

    // Create form, save title from builder, and create response table
    public function store(Request $request)
    {
        $validated = $request->validate([
            'header' => 'required|array',
            'header.title' => 'required|string|max:255',
            'header.image' => 'nullable|string',
        'header.logo' => 'nullable|string',
            'description' => 'nullable|string',
            'fields' => 'required|array',
            'footer' => 'nullable|array',
        ]);

        $fields = array_filter($validated['fields'], function ($f) {
            return !empty($f['label']) && !empty($f['name']) && is_string($f['label']) && is_string($f['name']);
        });

        $form = Form::create([
            'user_id'    => Auth::id(),
            'title'      => $validated['header']['title'],
            'description'=> $validated['description'] ?? '',
            'fields'     => array_values($fields),
            'header'     => $validated['header'],
            'footer'     => $validated['footer'] ?? [],
        ]);

        FormSchemaManager::createResponseTableForForm($form->id, $form->fields);

        return $request->wantsJson()
            ? response()->json(['id' => $form->id])
            : redirect()->route('forms.show', $form);
    }

    // Edit a form (prefill builder)
    public function edit(Form $form)
    {
        if ($form->user_id !== Auth::id()) abort(403);
        return view('forms.builder', compact('form'));
    }

    // Update form and response table columns if fields change
    public function update(Request $request, Form $form)
    {
        if ($form->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'header' => 'required|array',
            'header.title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array',
            'footer' => 'nullable|array',
        ]);
        $fields = array_filter($validated['fields'], function ($f) {
            return !empty($f['label']) && !empty($f['name']) && is_string($f['label']) && is_string($f['name']);
        });

        // Sync per-form table columns (add/remove as needed)
        $oldFields = $form->fields ?? [];
        $oldNames = collect($oldFields)->pluck('name')->all();
        $newNames = collect($fields)->pluck('name')->all();

        foreach (array_diff($newNames, $oldNames) as $addedName) {
            $added = collect($fields)->firstWhere('name', $addedName);
            if ($added)
                FormSchemaManager::addColumnToResponseTable($form->id, $added);
        }
        foreach (array_diff($oldNames, $newNames) as $removedName) {
            FormSchemaManager::dropColumnFromResponseTable($form->id, $removedName);
        }

        $form->update([
            'title'      => $validated['header']['title'],
            'description'=> $validated['description'] ?? $form->description,
            'fields'     => array_values($fields),
            'header'     => $validated['header'],
            'footer'     => $validated['footer'] ?? $form->footer,
        ]);

        return $request->wantsJson()
            ? response()->json(['success' => true])
            : redirect()->route('forms.show', $form)->with('success', 'Form updated!');
    }

    // Show single form for owner (private)
    public function show(Form $form)
    {
        if ($form->user_id !== Auth::id()) abort(403);
        return view('forms.show', compact('form'));
    }

    // Public form show (for sharing/fill)
    public function publicShow(Form $form)
    {
        return view('forms.show', compact('form'));
    }

    // Optionally, preview before publishing
    public function preview(Form $form)
    {
        return view('forms.preview', compact('form'));
    }

    // Delete form and its dynamic response table
    public function destroy(Form $form)
    {
        if ($form->user_id !== Auth::id()) abort(403);
        FormSchemaManager::dropResponseTable($form->id);
        $form->delete();



        return redirect()->route('dashboard')->with('success', 'Form deleted.');
    }
}
