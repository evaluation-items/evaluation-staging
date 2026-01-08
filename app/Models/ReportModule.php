<?php
namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportModule extends Model {
    // use HasFactory;

    protected $table = 'itransaction.report_module_dates';
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
