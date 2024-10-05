<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FacultySession extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'order',
        'faculty_id',
        'created_by',
        'responsible_id',
        'place',
        'start_time',
        'total_hours',
        'schedual_end_time',
        'actual_start_time',
        'actual_end_time',
        'status',
        'reject_reason',
        'decision_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'schedual_end_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'faculty_session_user');
    }

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(TopicAgenda::class, 'faculty_session_topic');
    }
}
