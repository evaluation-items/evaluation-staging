<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model {
    protected $table = 'itransaction.communication';

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
