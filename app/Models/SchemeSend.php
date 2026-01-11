<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SchemeSend extends Model
{
    use HasFactory;

    protected $table ='itransaction.scheme_send';

     protected $fillable = [
        'dept_id',
        'draft_id',
        'user_id',
        'created_by',
        'created_at',
        'status_id','team_member_dd','remarks','return_eval_date','evaluation_sent_date','viewed','forward_btn_show','forward_id'
    ];
    public function users()
    {
        $userIds = array_filter(explode(',', $this->team_member_dd)); // Filter out empty values
        return User::whereIn('id', $userIds)->get();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'team_member_id', 'id');
    }
}