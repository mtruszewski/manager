<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\DataSurnames;
use App\DataNames;

class AdminDashboard extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
      $request->user()->authorizeRoles(array('admin', 'superadmin'));

      return view('admin.dashboard');
    }

    public function addName(Request $request)
    {
      $this->validate($request, [
        'names__name' => 'required|string',
      ]);

      $name = New DataNames;
      $name->name = mb_convert_case($request->names__name, MB_CASE_TITLE, "UTF-8");

      $name = DataNames::firstOrCreate([
        'name' => $name->name
      ]);

      if ($name->wasRecentlyCreated) return back()->with('name_added', 'Name added to database.');
      else return back()->with('name_exist', 'Name exists in database.');
    }

    public function addSurname(Request $request)
    {
      $this->validate($request, [
        'names__surname' => 'required|string',
      ]);

      $surname = New DataSurnames;
      $surname->surname = mb_convert_case($request->names__surname, MB_CASE_TITLE, "UTF-8");

      $surname = DataSurnames::firstOrCreate([
        'surname' => $surname->surname
      ]);

      if ($surname->wasRecentlyCreated) return back()->with('surname_added', 'Surname added to database.');
      else return back()->with('surname_exist', 'Surname exists in database.');
    }
}
