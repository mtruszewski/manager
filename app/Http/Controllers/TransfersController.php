<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Transfers;
use App\Rules\PlayerExistInTeam;
use App\Team;
use App\UserTactics;
use App\Players;

class TransfersController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function putOnTransferList($id, Request $request)
    {
        $player = DB::table('players')->where('id', $id)->first();
        $user_id = Auth::user()->id;
        $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

        $this->validate($request, [
          'player_id' => ['required', 'integer', new PlayerExistInTeam($team_id)],
          'price' => 'required|integer',
        ]);

          $transfer = new Transfers;
          $transfer->player_id = $request->player_id;
          $transfer->seller_id = $team_id;
          $transfer->price = $request->price;
          $transfer::firstOrCreate(
            [
              'player_id' => $transfer->player_id
            ],
            [
              'seller_id' => $transfer->seller_id,
              'price' => $transfer->price
            ]
          );

          return back()->with('player_on_tl', 'Player added to transfer list.');
    }

    public function transfersList()
    {
      $user_id = Auth::user()->id;
      $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');
      $attributes = playerAttributes();

      $perPage = 9999;

      $players = DB::table('transfers')
      ->select('players.*', 'transfers.*', 'team.name as teamName')
      ->where('team_id', '!=', $team_id)
      ->leftJoin('players', 'players.id', '=', 'player_id')
      ->leftJoin('team', 'team.id', '=', 'transfers.seller_id')
      ->paginate($perPage);

      return view('guest.transfers', ['players' => $players, 'attributes' => $attributes]);
    }

    public function transfersListFilters(Request $request )
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
      $players = DB::table('transfers')
      ->select('players.*', 'transfers.*', 'team.name as teamName')
      ->where('team_id', '!=', $team_id)
      ->leftJoin('players', 'players.id', '=', 'player_id')
      ->leftJoin('team', 'team.id', '=', 'transfers.seller_id');

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

      return view('guest.ajax.transfersFilters', ['players' => $players]);

    }


    function buyPlayer($player_id) 
    {

      $user_id = Auth::user()->id;
      $team_id = DB::table('team_user')->where('user_id', $user_id)->value('team_id');

      // check if player is on TL
      $transferData = Transfers::where('player_id', $player_id)->first()->toArray();

      // check if buyer has enough finances
      $buyerFinance = Team::where('id', $team_id)->value('finance');

      if ($transferData['price'] > $buyerFinance) {
        return back()->with('message', 'You dont have enough money!');
      }
      if (count($transferData)) {
        $seller_teamId = $transferData['seller_id'];

        // remove from tactic
        $playersInUse = UserTactics::where('team_id', $seller_teamId)
        ->select('pos_1', 'pos_2', 'pos_3', 'pos_4', 'pos_5', 'pos_6', 'pos_7', 'pos_8', 'pos_9', 'pos_10', 'pos_11', 'pos_12', 'pos_13', 'pos_14', 'pos_15', 'pos_16')
        ->first()
        ->toArray();
        // check if player is used in tactic
        if ( in_array($player_id, $playersInUse) ) {
          $findKey = array_search($player_id, $playersInUse);
          $seller_userId = Team::where('team_id', $seller_teamId)->leftJoin('team_user', 'team_user.team_id', '=', 'team.id')->value('user_id');
          $ut = New UserTactics;
          $seller_playersInTactic = $ut->findSelectedPlayers($seller_userId, $seller_teamId);
          $seller_playersNotInTactic = $ut->findNotSelectedPlayers($seller_teamId, $seller_playersInTactic);
          // put random player in tactic in position of sold player
          $getRandomPlayer = $seller_playersNotInTactic[rand(0, sizeOf($seller_playersNotInTactic)-1)];
          UserTactics::where('team_id', $seller_teamId)->update([$findKey => $getRandomPlayer['id']]);
        }
        // increase finance
        Team::where('id', $seller_teamId)->increment('finance', $transferData['price']);
        // decrease finance
        Team::where('id', $team_id)->decrement('finance', $transferData['price']);
        // move player to buyer's team
        Players::where('id', $player_id)->update(['team_id' => $team_id]);
        // remove player from TL
        Transfers::where('player_id', $player_id)->delete();
      }

      return back()->with('message', 'Player added to your team!');
    }
}
