<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {
    // use HasFactory;
    //public $incrementing = false;
    public $timestamps = false;
    protected $table = 'itransaction.attachments';
    protected $primaryKey  = 'scheme_id';

    protected $fillable = ['scheme_id','couch_doc_id','couch_rev_id'];
}
