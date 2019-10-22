<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseDetail extends Model
{
    protected $table = 'case_details';

    public function case(){
        return $this->belongsTo('App\Cases','caseId');
    }

    public function knight(){
        return $this->hasMany('App\Users','id','knightId');
    }
}
