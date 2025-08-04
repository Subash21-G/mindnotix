<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FormResponseController extends Controller
{
    /**
     * Store a new response for a given form in its dedicated table.
     */
    public function store(Request $request, Form $form)
    {
        // Build validation rules from the form's dynamic fields
        $rules = [];
        foreach ($form->fields as $field) {
            if (isset($field['name'])) {
                $rules[$field['name']] = $field['rules'] ?? 'nullable';
            }
        }
        $validatedData = $request->validate($rules);

        // Prepare for DB insert: handle files and multi-choice values
        $tableName = "responses_form_{$form->id}";
        $data = [];
        foreach ($form->fields as $field) {
            $fieldName = $field['name'];
            $value = $request->input($fieldName);

            // Handle file uploads
            if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                $value = $request->file($fieldName)->store('uploads', 'public');
            }
            // Handle checkboxes (multi-choice) - store as comma-separated
            if ($field['type'] === 'multiple_choice' && is_array($value)) {
                $value = implode(', ', $value);
            }
            $data[$fieldName] = $value;
        }

         $data['form_creator_id']   = $form->user_id;
    $data['responder_user_id'] = Auth::id(); // or null for guests
    $data['header']            = is_array($form->header) ? json_encode($form->header) : $form->header;
    $data['footer']            = is_array($form->footer) ? json_encode($form->footer) : $form->footer;

        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table($tableName)->insert($data);

        return redirect()
            ->route('forms.show', $form)
            ->with('success', 'Your response was submitted successfully!');
    }

    /**
     * Display all responses from the dedicated responses table for the form.
     */
    public function index(Form $form)
    {
        // Owner access check
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $tableName = 'responses_form_' . $form->id;
        $responses = DB::table($tableName)->orderByDesc('created_at')->paginate(15);

        // Convert created_at, updated_at to Carbon for all responses
        $responses->getCollection()->transform(function ($item) {
            if (!empty($item->created_at)) {
                $item->created_at = Carbon::parse($item->created_at);
            }
            if (!empty($item->updated_at)) {
                $item->updated_at = Carbon::parse($item->updated_at);
            }
            return $item;
        });

        return view('responses.index', [
            'form' => $form,
            'responses' => $responses
        ]);
    }

    /**
     * Delete a single response row from the dedicated responses table.
     */
    public function destroy(Form $form, $responseId)
    {
        // Owner check can be enforced at the route/controller level
        $tableName = 'responses_form_' . $form->id;
        DB::table($tableName)->where('id', $responseId)->delete();

        return back()->with('success', 'Response deleted.');
    }
}
