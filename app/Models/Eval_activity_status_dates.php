<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eval_activity_status_dates extends Model {
    protected $table = 'itransaction.eval_activity_status_dates';

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
