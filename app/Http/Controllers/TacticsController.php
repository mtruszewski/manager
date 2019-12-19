<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use App\UserTactics;
use App\Rules\PlayerExistInTeam;

class TacticsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function tactics()
    {

        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $players = DB::table('players')
            ->where('team_id', $team_id)
            ->orderBy('number', 'asc')
            ->get()
            ->toArray();

        // IF TACTIC EXIST
        if (DB::table('user_tactics')->where('team_id', $team_id)->where('user_id', $user_id)->count() > 0) {
            
            // tactic
            $tactics = DB::table('user_tactics')
            ->where('team_id', $team_id)
            ->where('user_id', $user_id)
            ->get();
            
            // saved squad
            $userTactics = New UserTactics;
            $selectedPlayers = $userTactics->findSelectedPlayers($user_id, $team_id);
            $notSelectedPlayers = $userTactics->findNotSelectedPlayers($team_id, $selectedPlayers);

            return view('guest.tactics', ['players' => $players, 'tactics' => $tactics, 'selectedPlayers' => $selectedPlayers, 'notSelectedPlayers' => $notSelectedPlayers]);
        }

        else return view('guest.tactics', ['players' => $players]);

    }

    public function saveTactic(Request $request)
    {
        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $tactics__json = json_decode($request->tactics__json, true);
        // dd($tactics__json);
        $rules = [
            'pos_1' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_2' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_3' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_4' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_5' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_6' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_7' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_8' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_9' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_10' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_11' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_12' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_13' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_14' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_15' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'pos_16' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)], 
            'formation' => 'bail|required|string',
            'captain' => ['bail', 'required', 'integer', new PlayerExistInTeam($team_id)]
        ];

        $validator = Validator::make($tactics__json, $rules);
        // dd($validator->errors()->all());

        if ($validator->passes()) {

            $saveTactic = new UserTactics;
            $saveTactic = UserTactics::updateOrCreate(
            [
                'user_id' => $user_id,
                'team_id' => $team_id,
            ],
            [
                'formation' => $tactics__json['formation'],
                'captain' => $tactics__json['captain'],
                'pos_1' => $tactics__json['pos_1'],
                'pos_2' => $tactics__json['pos_2'],
                'pos_3' => $tactics__json['pos_3'],
                'pos_4' => $tactics__json['pos_4'],
                'pos_5' => $tactics__json['pos_5'],
                'pos_6' => $tactics__json['pos_6'],
                'pos_7' => $tactics__json['pos_7'],
                'pos_8' => $tactics__json['pos_8'],
                'pos_9' => $tactics__json['pos_9'],
                'pos_10' => $tactics__json['pos_10'],
                'pos_11' => $tactics__json['pos_11'],
                'pos_12' => $tactics__json['pos_12'],
                'pos_13' => $tactics__json['pos_13'],
                'pos_14' => $tactics__json['pos_14'],
                'pos_15' => $tactics__json['pos_15'],
                'pos_16' => $tactics__json['pos_16']
            ]);

            return back()->with('tactic_saved', 'Tactic added to database.');

        } else {
            return back()->with('tactic_failed', 'Error. You have to use 16 players.');
        }

    }

}
