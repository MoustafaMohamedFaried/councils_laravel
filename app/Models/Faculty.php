<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'en_name', 'ar_name', 'headquarter_id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class);
    }
}
