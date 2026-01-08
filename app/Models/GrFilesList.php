<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrFilesList extends Model {
    // use HasFactory;
    protected $table = 'itransaction.gr_files_list';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $guarded = [];
}
