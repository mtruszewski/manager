<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Matches;
use App\UserTactics;

class MatchesController extends Controller
{
    function challenge()
    {

        $user_id = Auth::user()->id;
        $userTactics = new UserTactics;

        // User team
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');
        $userTeam = DB::table('team_user')->where('team_user.user_id', $user_id)
        ->leftJoin('team', 'team.id', "=", 'team_user.id')
        ->leftJoin('user_tactics', 'user_tactics.team_id', '=', 'team.id')
        ->first();

        // tactic
        $tactics = DB::table('user_tactics')
        ->where('team_id', $team_id)
        ->where('user_id', $user_id)
        ->get();
        
        // saved squad
        $userSelectedPlayers = $userTactics->findSelectedPlayers($user_id, $team_id);
        $userNotSelectedPlayers = $userTactics->findNotSelectedPlayers($team_id, $userSelectedPlayers);


        // Opponent team
        $randomTeam = DB::table('team_user')->where('team_user.user_id', "!=", $user_id)
        ->inRandomOrder()
        ->leftJoin('team', 'team.id', '=', 'team_user.team_id')
        ->leftJoin('user_tactics', 'user_tactics.team_id', '=', 'team.id')
        ->where('user_tactics.user_id', "!=", $user_id)
        ->first();
        $opponentUserID = $randomTeam->user_id;
        $opponentTeamID = $randomTeam->team_id;

        // saved squad
        $opponentSelectedPlayers = $userTactics->findSelectedPlayers($opponentUserID, $opponentTeamID);
        $opponentNotSelectedPlayers = $userTactics->findNotSelectedPlayers($opponentTeamID, $opponentSelectedPlayers);


        // Match simulator
        $match = New Matches;
        $matchInfo = $match->simulator($team_id, $opponentTeamID);
        // dd($matchInfo);

        return view('guest.challenge', [
            'userTeam' => $userTeam,
            'userSelectedPlayers' => $userSelectedPlayers,
            'userNotSelectedPlayers' => $userNotSelectedPlayers,
            'randomTeam' => $randomTeam,
            'opponentSelectedPlayers' => $opponentSelectedPlayers,
            'opponentNotSelectedPlayers' => $opponentNotSelectedPlayers,
            'matchInfo' => $matchInfo
        ]);
    }
}
