<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'ar_name', 'en_name', 'faculty_id'];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
