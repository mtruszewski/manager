<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PlayersController;

class RegisterTeamController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function createTeam(Request $request)
  {
        $this->validate($request, [
            'createTeam__name' => 'required|string',
        ]);

        $team = new Team;
        $team->name = $request->createTeam__name;
        $team->save();

        $user_id = Auth::user()->id;
        $team_id = $team->id;
        $team->assignTeamToUser()->attach($user_id);


        for($i = 0; $i <= 25; $i++) {
          (new PlayersController)->createPlayer();
        }
        
        return back()->with('success', 'Team has been created.');
    }
}
