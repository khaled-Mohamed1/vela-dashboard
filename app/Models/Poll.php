<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{

    protected $connection = 'mysql_2';
    protected $table = 'polls';

    use SoftDeletes;
    protected $fillable=[
        'message_id',
        'poll_options',
    ];

    public function PollMessage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }

}
