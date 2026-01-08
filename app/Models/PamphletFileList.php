<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamphletFileList extends Model {
    // use HasFactory;

    protected $table = 'itransaction.pamphlet_file_list';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['id','scheme_id','file_name'];
}
