<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityApproval extends Model {
    protected $table = 'itransaction.activity_approval';

    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    protected $primaryKey = 'id';

    protected $guarded = [];
}
