<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meet_id',
    ];

    public function MeetInviteUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function MeetInviteMeet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Meet::class, 'meet_id', 'id');
    }

}
