<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'status',
        'started_at',
        'ended_at',
        'initial_question',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the user who initiated the chat.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the agent assigned to the chat.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the messages for the chat session.
     */
    public function messages()
    {
        return $this->hasMany(LiveChatMessage::class, 'session_id');
    }
}
