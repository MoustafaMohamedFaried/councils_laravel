<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'order',
        'main_topic_id'
    ];

    public function mainTopic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'main_topic_id')
            ->whereNull('main_topic_id')
            ->whereNot('id', $this->id);
    }

    public function subTopic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'main_topic_id')
            ->whereNotNull('main_topic_id');
    }
}
