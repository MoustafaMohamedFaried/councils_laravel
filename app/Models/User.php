<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'position_id',
        'headquarter_id',
        'faculty_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position(): BelongsTo
    {
        return $this->BelongsTo(Position::class);
    }

    public function headquarter(): BelongsTo
    {
        return $this->BelongsTo(Headquarter::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->BelongsTo(Faculty::class);
    }

    public function councils(): BelongsToMany
    {
        return $this->belongsToMany(FacultyCouncil::class);
    }
}
