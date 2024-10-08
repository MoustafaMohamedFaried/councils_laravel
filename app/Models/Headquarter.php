<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Headquarter extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'en_name', 'ar_name', 'address'];

    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }
}
