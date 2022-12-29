<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';
    protected $table = 'messages';

    protected $fillable=[
        'user_id',
        'conversations_id',
        'message',
        'read',
        'is_image',
        'is_file',
        'is_voice',
        'is_poll',
        'parent_id'
    ];

    public function MessageConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversations_id', 'id');
    }

    public function MessageUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
