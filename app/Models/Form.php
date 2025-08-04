<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'fields',
        'header',
        'footer'
    ];

    protected $casts = [
        'fields' => 'array',
        'header' => 'array',
        'footer' => 'array',
    ];

    /**
     * The user who created/owns the form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fetch all responses for this form from its dynamic response table.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResponses()
    {
        $tableName = "responses_form_{$this->id}";
        if (DB::getSchemaBuilder()->hasTable($tableName)) {
            return DB::table($tableName)->orderByDesc('created_at')->get();
        }
        return collect();
    }
}
