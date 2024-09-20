<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionDepartmentDecision extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'agenda_id',
        'decision',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionDepartment::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(TopicAgenda::class);
    }
}
