<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table ='users';
    public $incrementing = false;

    public function certificationInfo(){
        return $this->hasMany('App\CetificationInformation','userId');
    }
}
