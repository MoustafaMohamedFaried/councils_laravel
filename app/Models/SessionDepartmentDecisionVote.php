<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionDepartmentDecisionVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'decision_id',
        'status'
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionDepartment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function decision(): BelongsTo
    {
        return $this->belongsTo(SessionDepartmentDecision::class, 'decision_id');
    }
}
