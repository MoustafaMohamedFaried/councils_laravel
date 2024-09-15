<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SessionDepartment extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'order',
        'department_id',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
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
        return $this->belongsToMany(User::class, 'session_department_user');
    }

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(TopicAgenda::class, 'session_department_topics');
    }
}
