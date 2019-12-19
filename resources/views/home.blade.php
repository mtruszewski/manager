@extends('guest.menu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h2>Dashboard</h2></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="card">
                <div class="card-header">Create player</div>
                <div class="card-body">
                    <div class="createPlayer">
                        <form class="createPlayer__form border border-light" action="{{ route('user_createPlayer') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <button class="btn btn-info btn-block" type="submit">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
        @inject('team', 'App\Team')
        @if (!$team->hasTeam(Auth::user()->id))
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h2>Create team</h2></div>
                <div class="card-body">
                    <div class="createTeam">
                        <form class="createTeam__form border border-light" action="{{ route('user_createTeam') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="text" name="createTeam__name" id="createTeam__name" class="form-control" placeholder="Team name">
                            <button class="btn btn-info btn-block" type="submit">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if ($team->hasTeam(Auth::user()->id))
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h2>Team information</h2></div>
                <div class="card-body">
                    <div class="dashboard__teamName d-flex justify-content-between">
                        <span class="name">Name: </span>
                        <span class="value">{{ $teamInfo->name }}</span>
                    </div>
                    <div class="dashboard__teamId d-flex justify-content-between">
                        <span class="name">ID: </span>
                        <span class="value">{{ $teamInfo->id }}</span>
                    </div>
                    <div class="dashboard__teamCreatedAt d-flex justify-content-between">
                        <span class="name">Created at: </span>
                        <span class="value">{{ $teamInfo->created_at }}</span>
                    </div>
                    <div class="dashboard__teamFinance d-flex justify-content-between">
                        <span class="name">Finance: </span>
                        <span class="value"><strong>{{ $teamInfo->finance }} <i class="fas fa-coins"></i></strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h2>Transfer list</h2></div>
                <div class="card-body">
                    <div class="dashboard__transfers">
                        @foreach ($transfers as $player)
                        <div class="dashboard__transfers--single d-flex justify-content-between">
                            <span class="name">{{ findplayer($player->player_id)->name }} {{ findplayer($player->player_id)->surname }}</span>
                            <span class="value"><strong>{{ $player->price }} <i class="fas fa-coins"></i></strong></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
