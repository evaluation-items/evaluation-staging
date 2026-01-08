<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sdggoals extends Model {
    protected $table ='itransaction.goals';
    protected $primaryKey = 'goal_id';
    public $timestamps = false;
    protected $guarded = [];
}

