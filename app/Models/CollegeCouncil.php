<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollegeCouncil extends Model
{
    use HasFactory;

    protected $fillable = ['session_id','status','reject_reason'];

    public function session() : BelongsTo
    {
        return $this->belongsTo(SessionDepartment::class,'session_id');
    }

    public function agenda() : BelongsTo
    {
        return $this->belongsTo(TopicAgenda::class,'agenda_id');
    }
}
