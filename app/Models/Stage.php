<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\SchemeSend;

class Stage extends Model
{
    use HasFactory;

    protected $table ='itransaction.stages';
    protected $primaryKey = 'id';
    protected $guarded = [];


    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function schemeSend()
    {
        return $this->belongsTo(SchemeSend::class, 'draft_id', 'draft_id');
    }
}
