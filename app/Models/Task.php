<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
//        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];


    public function AdminTask(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskNote::class);
    }

    public function userTask()
    {
        return $this->hasMany(TaskUser::class, 'task_id', 'id');
    }

}
