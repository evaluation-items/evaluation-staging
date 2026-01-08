<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User_user_role;
use App\Models\BranchRole;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'public.branches';
    protected $fillable = ['name', 'status'];
    
    public function roles()
    {
        return $this->belongsToMany(User_user_role::class, BranchRole::class, 'branch_id', 'role_id');
    }
    public function role()
    {
        return $this->hasMany(BranchRole::class, 'branch_id', 'id');
    }
}
