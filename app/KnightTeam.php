<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KnightTeam extends Model
{
    protected $table = 'knight_teams';

    public function knight(){
        return $this->hasMany('App\Users','team_id')->whereIn('status',[1,2,3]);
    }
}
