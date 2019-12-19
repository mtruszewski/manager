<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Transfers;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(Request $request)
  {

    // Team informations
    $user_id = Auth::user()->id;
    $team_id = DB::table('team_user')->where('user_id', $user_id)->value('id');

    $teamInfo = New Team;
    $teamInfo = Team::where('id', $team_id)->first();

    // Players on TL
    $transfers = New Transfers;
    $transfers = Transfers::where('seller_id', $team_id)->orderBy('price', 'desc')->get();


    // Admin dashboard
    $request->user()->authorizeRoles(['guest', 'admin', 'superadmin']);

    if ($request->user()->hasRole('superadmin')) 
    {
      return view('admin.dashboard');
    }
    else 
    {
      return view('home', [
        'teamInfo' => $teamInfo,
        'transfers' => $transfers,
      ]);
    }
  }
}
