<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FormSchemaManager
{
    /**
     * Create a responses table for a specific form ID, matching its fields.
     * Table will be named 'responses_form_{formId}'.
     */
    public static function createResponseTableForForm($formId, $fields)
    {
        $tableName = "responses_form_{$formId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) use ($fields) {
                $table->id();

                     // --- ADD THESE LINES FOR YOUR NEW COLUMNS ---
            $table->unsignedBigInteger('form_creator_id')->nullable(); // The form owner's user id
            $table->unsignedBigInteger('responder_user_id')->nullable(); // The responder's user id
            $table->json('header')->nullable(); // Whole form header config at response time
            $table->json('footer')->nullable(); // Whole form footer config at response time
                // Optionally, add user_id or other meta columns here!
                foreach ($fields as $field) {
                    $col = self::sanitizeColumn($field['name'] ?? '');
                    switch ($field['type'] ?? 'short_answer') {
                        case 'paragraph':
                            $table->text($col)->nullable();
                            break;
                        case 'file':
                            $table->string($col)->nullable();
                            break;
                        case 'age':
                            $table->integer($col)->nullable();
                            break;
                        default:
                            $table->string($col)->nullable();
                    }
                }
                $table->timestamps();
            });
        }
    }

    /**
     * Add a single field/column to the form's responses table.
     */
    public static function addColumnToResponseTable($formId, $field)
    {
        $tableName = "responses_form_{$formId}";
        $col = self::sanitizeColumn($field['name'] ?? '');
        if (!Schema::hasColumn($tableName, $col)) {
            Schema::table($tableName, function (Blueprint $table) use ($field, $col) {
                switch ($field['type'] ?? 'short_answer') {
                    case 'paragraph':
                        $table->text($col)->nullable();
                        break;
                    case 'file':
                        $table->string($col)->nullable();
                        break;
                    case 'age':
                        $table->integer($col)->nullable();
                        break;
                    default:
                        $table->string($col)->nullable();
                }
            });
        }
    }

    /**
     * Remove a field/column from a form's responses table.
     */
    public static function dropColumnFromResponseTable($formId, $fieldName)
    {
        $tableName = "responses_form_{$formId}";
        $col = self::sanitizeColumn($fieldName);
        if (Schema::hasColumn($tableName, $col)) {
            Schema::table($tableName, function (Blueprint $table) use ($col) {
                $table->dropColumn($col);
            });
        }
    }

    /**
     * Drop the whole response table for a form (e.g. when form is deleted).
     */
    public static function dropResponseTable($formId)
    {
        $tableName = "responses_form_{$formId}";
        Schema::dropIfExists($tableName);
    }

    /**
     * Sanitize column/field name to be safe for DB column.
     */
    protected static function sanitizeColumn($name)
    {
        $col = preg_replace('/[^a-zA-Z0-9_]/', '_', $name ?? '');
        if (empty($col)) {
            $col = 'field_' . uniqid();
        }
        return $col;
    }
}
