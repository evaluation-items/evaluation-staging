<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class BranchRole extends Model
{
    use HasFactory;
    protected $table = 'public.branch_role_table';
    protected $fillable = ['branch_id', 'role_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
