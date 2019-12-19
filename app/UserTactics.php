<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserTactics extends Model
{
    protected $table = 'user_tactics';

    protected $fillable = [
        'user_id',
        'team_id',
        'formation',
        'captain',
        'pos_1',
        'pos_2',
        'pos_3',
        'pos_4',
        'pos_5',
        'pos_6',
        'pos_7',
        'pos_8',
        'pos_9',
        'pos_10',
        'pos_11',
        'pos_12',
        'pos_13',
        'pos_14',
        'pos_15',
        'pos_16',
    ];

    function findSelectedPlayers($user_id, $team_id)
    {
        $squad = UserTactics::where('team_id', $team_id)
        ->where('user_id', $user_id)
        ->select('pos_1', 'pos_2', 'pos_3', 'pos_4', 'pos_5', 'pos_6', 'pos_7', 'pos_8', 'pos_9', 'pos_10', 'pos_11', 'pos_12', 'pos_13', 'pos_14', 'pos_15', 'pos_16')
        ->get()
        ->toArray();

        $selectedPlayers = [];
        $ind = 0;
        foreach($squad[0] as $squad) {
            $ind++;
            $selectedPlayers[$ind] = $squad;
        }

        return $selectedPlayers;
    }

    function findNotSelectedPlayers($team_id, $selectedPlayers)
    {
        $notSelectedPlayers = Players::where('team_id', $team_id)
        ->whereNotIn('id', array_values($selectedPlayers))
        ->orderBy('number', 'asc')
        ->get()
        ->toArray();

        return $notSelectedPlayers;
    }

    function hasTactic($user_id)
    {
        $user_tactics = DB::table('user_tactics')->where('user_id', $user_id)->first();
        return null !== $user_tactics;
    }
}
