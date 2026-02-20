<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Beneficiaries extends Model
{
    use HasFactory;

    protected $table ='imaster.beneficiaries_item';
    protected $guarded = [];
    protected $primaryKey = 'id';

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', true);
    }
}
