@extends('guest.menu')

@section('content')
<section class="tactics">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Tactics</h2>
                    </div>
                    <div class="card-body">
                        @if($message = Session::get('tactic_added'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        @if($message = Session::get('tactic_failed'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        <div class="form-group">
                            <select class="form-control" id="tactics__formation">
                                <option value="442">4-4-2</option>
                                <option value="433" @if (isset($tactics)) @if ($tactics[0]->formation === '433') selected @endif @endif>4-3-3</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="tactics__captain" class="col-2 col-form-label">Captain:</label>
                            <div class="col-10">
                                <select class="form-control" id="tactics__captain">
                                @if (isset($tactics))
                                    @foreach ($selectedPlayers as $index => $sq)
                                        @if ($index <= 11 && $tactics)
                                        <option value="{{ findPlayer($sq)->id }}" @if (findPlayer($sq)->id == $tactics[0]->captain) selected @endif>
                                            {{ findPlayer($sq)->number }}. {{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}
                                        </option>
                                        @endif
                                    @endforeach
                                @else
                                        <option value="">No players on the field</option>
                                @endif
                                </select>
                            </div>
                        </div>
                        <div id="tactics__box" class="tactics__field @if (isset($tactics)) f_{{$tactics[0]->formation}} @else f_442 @endif">
                        @if (isset($tactics))
                            @foreach ($selectedPlayers as $index => $sq)
                            <div data-position="{{ $index }}" class="position_{{ $index }} empty-field remove-background">
                                <div class="player__tshirt" data-player-id="{{ findPlayer($sq)->id }}" data-player-name="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}">
                                    <img src="{{ asset('/images/tshirt.png') }}" alt="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}"/>
                                    <span class="player__number">{{ findPlayer($sq)->number }}</span>
                                </div>
                                <div class="player__name">
                                    {{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}
                                </div>
                            </div>
                            @endforeach
                        @else
                            @for ($i = 1; $i <= 16; $i++)
                            <div data-position="{{ $i }}" class="position_{{ $i }} empty-field"></div>
                            @endfor
                        @endif
                        </div>
                        <form class="tactics__form border border-light" action="{{ route('user_saveTactic') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="tactics__json">
                        </form>
                        <button id="tactics__save" class="btn btn-info btn-block">Save tactic</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h2>Team</h2>
                    </div>
                    <div class="card-body">
                        <div class="players">
                            @if (isset($tactics))
                                @foreach($notSelectedPlayers as $player)
                                <div class="player">
                                    <div class="player__tshirt" data-player-id="{{ $player['id'] }}" data-player-name="{{ $player['name'] }} {{ $player['surname'] }}">
                                        <img src="{{ asset('/images/tshirt.png') }}" alt="{{ $player['name'] }} {{ $player['surname'] }}"/>
                                        <span class="player__number">{{ $player['number'] }}</span>
                                    </div>
                                    <div class="player__name">
                                        {{ $player['name'] }} {{ $player['surname'] }}
                                    </div>
                                </div>
                                @endforeach
                            @else
                                @foreach($players as $player)
                                <div class="player">
                                    <div class="player__tshirt" data-player-id="{{ $player->id }}" data-player-name="{{ $player->name }} {{ $player->surname }}">
                                        <img src="{{ asset('/images/tshirt.png') }}" alt="{{ $player->name }} {{ $player->surname }}"/>
                                        <span class="player__number">{{ $player->number }}</span>
                                    </div>
                                    <div class="player__name">
                                        {{ $player->name }} {{ $player->surname }}
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card balls">
                    <div class="card-header">
                        <h2>Player</h2>
                    </div>
                    <div class="card-body">                
                        @foreach($players as $player)
                        <div class="player__popup" data-player-id="{{ $player->id }}">
                            @include('card.player')
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection