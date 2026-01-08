<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enumerator_list extends Model {
    use HasFactory;
    protected $table = 'itransaction.enumerator_list';

    protected $fillable = ['id','submitted_by','submitted_at','q1_name_of_beneficiary','q2_respondend','q3_address_of_beneficiary','q2_1_name_of_area','q2_2_type_of_area','q3_name_of_taluka_zone','q3_1_name_of_district_mnp','q4_sex','q5_age','q6_contact_number','instance_id','created_at','updated_at'];
}
