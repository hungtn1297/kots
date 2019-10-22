<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseDetail extends Model
{
    protected $table = 'case_details';

    public function case(){
        return $this->belongsTo('App\Cases','caseId');
    }
}
