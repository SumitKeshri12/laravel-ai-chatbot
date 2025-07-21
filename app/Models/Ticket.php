<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'rasa_response',
        'status',
        'answer',
    ];

    /**
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
