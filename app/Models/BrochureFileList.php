<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrochureFileList extends Model {
    // use HasFactory;
    protected $table = 'itransaction.brochure_file_list';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['id','scheme_id','file_name'];
}
