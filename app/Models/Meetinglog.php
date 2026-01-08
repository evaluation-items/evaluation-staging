<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetinglog extends Model {
    // use HasFactory;
    protected $table = 'itransaction.meetinglogs';
    protected $primaryKey = 'mid';
    public $timestamps = false;
    protected $fillable = ['mid','draft_id','dept_id','im_id','subject','description','date','time','level','created_by','created_at'];
}
