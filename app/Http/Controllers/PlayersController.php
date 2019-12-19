<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\Players;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\DataNames;
use App\DataSurnames;

class PlayersController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function createPlayer()
    {

        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $player = new Players;

        $player->name = DataNames::inRandomOrder()->value('name');
        $player->surname = DataSurnames::inRandomOrder()->value('surname');

        // generate number for player
        $numbersExistInDB = DB::table('players')->where('team_id', $team_id)->select('number')->get()->toArray();
        
        if ($numbersExistInDB) {

            $numbersExistArray = [];
            $ind = 0;
            foreach($numbersExistInDB[0] as $number) {
                $ind++;
                $numbersExistArray[$ind] = $number;
            }
            $numbers = array_values($numbersExistArray);

            do {
                $numberRand = rand(1, 200);
            } while(in_array($numberRand, $numbers));

        }
        else {
            $numberRand = rand(1, 200);
        }

        $player->number = $numberRand;


        // basic stats
        $player->age = Carbon::now()->subYear(rand(18, 28))->subMonths(rand(0, 12))->subDays(rand(0, 31));
        $player->height = rand(150, 200);
        $player->weight = rand($player->height-10, $player->height+10) - 100;

        // foot
        $rollDice = rand(0, 2);
        if ($rollDice === 0) $foot = 'L';
        elseif ($rollDice === 1) $foot = 'R';
        else $foot = 'B';
        $player->foot = $foot;

        // STATS
        $range = $player->rollDiceForStats($player->age);
        $player->speed = rand($range[2], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->stamina = rand($range[2], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->intelligence = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->short_pass = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->long_pass = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->ball_control = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->heading = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->shooting = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->tackling = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->set_plays = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->keeping = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->experience = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->tackling = rand($range[0], $range[1]) / 10;
        $range = $player->rollDiceForStats($player->age);
        $player->form = rand($range[0], $range[1]) / 10;

        $player->team_id = $team_id;

        $player->save();

        return back()->with('success', 'Players generated.');
    }

    public function playersList(Request $request )
    {

        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $orderBy = 'number';
        $sortBy = 'asc';
        $perPage = 9999;
        $speedFrom = 0;
        $speedTo = 10;

        
        $attributes = playerAttributes();
        // query
        $players = DB::table('players');
        $players = $players->select('players.*', 'transfers.id as tl');
        $players = $players->where('team_id', $team_id);
        
        // check if player is on transfer list
        $players = $players->leftJoin('transfers', 'transfers.player_id', '=', 'players.id');

        $players = $players->orderBy($orderBy, $sortBy)->paginate($perPage);

        return view('guest.players', ['players' => $players, 'attributes' => $attributes]);

    }


    public function playersListFilters(Request $request )
    {

        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $orderBy = 'number';
        $sortBy = 'asc';
        $perPage = 6;
        $speedFrom = 0;
        $speedTo = 10;

        // get list of attributes
        $attributes = playerAttributes();
        // query
        $players = DB::table('players');
        $players = $players->select('players.*', 'transfers.id as tl');
        $players = $players->where('team_id', $team_id);

        // check if player is on transfer list
        $players = $players->leftJoin('transfers', 'transfers.player_id', '=', 'players.id');

        // filters
        $filter_by = $request->input('filter');
        if($filter_by!=NULL){
            foreach($filter_by as $key => $filters) {
                if ($key == 'orderBy') $orderBy = $filters;
                if ($key == 'sortBy') $sortBy = $filters;
                foreach($attributes as $attr) {
                    if ($key == $attr) {
                        $min = $filters[0];
                        $max = $filters[1];
                        if ($min === $max) $players = $players->where($attr, $max);
                        else $players = $players->whereBetween($attr, [$min, $max]);
                    }
                }
            }
        }

        $players = $players->orderBy($orderBy, $sortBy)->paginate($perPage);

        return view('guest.ajax.playersFilters', ['players' => $players]);

    }
}
