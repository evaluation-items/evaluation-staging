<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterStateFiles extends Model {
    // use HasFactory;

    protected $table = 'itransaction.center_state_file_list';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['id','scheme_id','file_name'];
}
