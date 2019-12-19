@extends('guest.menu')

@section('content')
<section class="challenge">
    <div class="container">
        <div class="row justify-content-center balls">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Match</h2>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-4">
                                <div class="challenge__buttons pb-4">
                                    <button class="btn btn-info btn-block live">LIVE</button>
                                    <button class="btn btn-info btn-block result">SHOW SCORE</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="tactics">
                                    <h2>{{ $userTeam->name }}</h2>
                                    <div class="tactics__field f_{{ $userTeam->formation }}">
                                        @foreach ($userSelectedPlayers as $index => $sq)
                                        <div data-position="{{ $index }}" class="position_{{ $index }} empty-field remove-background">
                                            <div class="player__tshirt" data-player-id="{{ findPlayer($sq)->id }}" data-player-name="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}">
                                                <img src="{{ asset('/images/tshirt.png') }}" alt="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}"/>
                                                <span class="player__number">{{ findPlayer($sq)->number }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tactics">
                                    <h2>{{ $randomTeam->name }}</h2>
                                    <div class="tactics__field f_{{ $randomTeam->formation }}">
                                        @foreach ($opponentSelectedPlayers as $index => $sq)
                                        <div data-position="{{ $index }}" class="position_{{ $index }} empty-field remove-background">
                                            <div class="player__tshirt" data-player-id="{{ findPlayer($sq)->id }}" data-player-name="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}">
                                                <img src="{{ asset('/images/tshirt.png') }}" alt="{{ findPlayer($sq)->name }} {{ findPlayer($sq)->surname }}"/>
                                                <span class="player__number">{{ findPlayer($sq)->number }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center balls">
            <div class="col-md-12">
                <div class="py-4">
                <div class="card commentary">
                    <div class="card-header">
                        <h2>Commentary</h2>
                    </div>
                    <div class="card-body">
                        <div class="commentary__timer">0'</div>
                        <ul class="commentary__list">
                        @foreach($matchInfo as $key => $comm)
                            <li data-commentary-minute="{{ $comm['minute'] }}">{{ $comm['minute'] }}. {!! $comm['commentary'] !!}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection