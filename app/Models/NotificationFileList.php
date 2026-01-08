<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationFileList extends Model {
    // use HasFactory;
    protected $table = 'itransaction.notification_files_list';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $guarded = [];
}
