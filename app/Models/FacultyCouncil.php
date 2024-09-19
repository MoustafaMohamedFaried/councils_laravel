<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacultyCouncil extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','faculty_id','position_id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }
}
