<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SchemeSend;
use App\Models\Stage;
use App\Models\User_user_role;
use App\Models\Department;
use Illuminate\Auth\Notifications\ResetPassword;


class User extends Authenticatable implements MustVerifyEmail{
    
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
        'id',
        'name',
        'email',
        'role',
        'phone',
        'picture',
        'password',
        'dept_id','role_manage','login_user','welcome_popup','is_first_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getPictureAttribute($value){
        if($value){
            return asset('users/images/'.$value);
        }else{
            return asset('users/images/no-image.png');
        }
    }
    public function schemeSends()
    {
        return $this->hasMany(SchemeSend::class, 'team_member_id', 'id');
    }
    public function stages()
    {
        return $this->hasMany(Stage::class, 'user_id');
    }
    public function roles()
    {
        return $this->hasMany(User_user_role::class,'id','role');
    }
    public function department()
    {
        return $this->hasMany(Department::class,'dept_id','dept_id');
    }
    public function sendPasswordResetNotification($token)
    {
        \Log::info('Reset token: ' . $token);

        $this->notify(new ResetPassword($token));
    }
}
