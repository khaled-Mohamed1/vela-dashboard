<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';
    protected $table = 'conversations';

    protected $fillable=[
        'name',
        'image',
        'type',
        'last_time_message',
        'admin_id',
        'sender_id',
        'receiver_id',
        'company_NO',
        'company_group'
    ];

    public function SenderConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {

        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function ReceiverConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function usersConversation()
    {
        return $this->hasMany(Participant::class , 'conversations_id' , 'id');
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class, 'conversations_id' , 'id');
    }


}
