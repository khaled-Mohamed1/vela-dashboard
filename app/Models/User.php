<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;
//    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $connection = 'mysql';


    protected $fillable = [
        'full_name',
        'email',
        'password',
        'job',
        'phone_NO',
        'phone_NO2',
        'phone_NO3',
        'image',
        'company_NO',
        'company_name',
        'role_id',
        'status',
        'private_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'avatar_url',
    ];

    public function UserSConversation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class,'sender_id','id');
    }

    public function UserRConversation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class,'receiver_id','id');
    }

    public function taskAdmins()
    {
        return $this->hasMany(Task::class, 'admin_id', 'id');
    }

    public function taskUser()
    {
        return $this->hasMany(TaskUser::class, 'user_id', 'id');
    }

    public function taskUserNotes()
    {
        return $this->hasMany(TaskNote::class, 'user_id', 'id');
    }

    public function taskAdminNotes()
    {
        return $this->hasMany(TaskNote::class, 'admin_id', 'id');
    }

    public function todolistUser()
    {
        return $this->hasMany(ToDoList::class, 'user_id', 'id');
    }

    public function receivedMessages()
    {
        return $this->belongsToMany(Message::class, 'recipients')
            ->withPivot([
                'read_at', 'deleted_at',
            ]);
    }
//
    public function getAvatarUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . $this->name;
    }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
