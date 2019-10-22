<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $table = 'cases';

    public function user(){
        return $this->belongsTo('App\Users','citizenId');
    }

    public function caseDetail(){
        return $this->hasMany('App\CaseDetail','caseId');
    }
}
