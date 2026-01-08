<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingsViewedBy extends Model {

    protected $table = 'itransaction.meetings_viewed_by';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public $timestamps = false;
    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

}
