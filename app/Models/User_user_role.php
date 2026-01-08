<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class User_user_role extends Model {
    // use HasFactory;
    protected $table = 'public.user_role';
    protected $fillable = ['id','rolename','active','created_at','updated_at','status'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
