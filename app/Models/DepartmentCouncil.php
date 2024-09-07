<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartmentCouncil extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','department_id','position_id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
