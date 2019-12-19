<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use DB;

class Team extends Model
{
    protected $table = 'team';

    protected $fillable = [
        'name'
    ];

    public function assignTeamToUser()
    {
        return $this->belongsToMany('App\User', 'team_user', 'team_id', 'user_id');
    }

    public function hasTeam($user_id)
    {
        $team_user = DB::table('team_user')->where('user_id', $user_id)->first();
        return null !== $team_user;
    }

    public function getFinance($team_id)
    {
        return Team::where('id', $team_id)->value('finance');
    }
}