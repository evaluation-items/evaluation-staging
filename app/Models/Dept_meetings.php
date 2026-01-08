<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dept_meetings extends Model {
    protected $table = 'itransaction.dept_meetings';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
    protected $guarded = [];
}
