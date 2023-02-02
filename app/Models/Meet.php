<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'about',
        'start_date',
        'start_time',
        'link',
    ];


    public function UserMeet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function meetInvites()
    {
        return $this->hasMany(MeetInvite::class, 'meet_id', 'id');
    }

}
