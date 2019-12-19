<div class="row balls">
@foreach($players as $player)
    <div class="player col-6 pb-4">
        <div class="card">
            <div class="card-header">
                <h4>{{ $player->number }}. {{ $player->name }} {{ $player->surname }}</h4>
            </div>
            <div class="card-body">
                @include('card.player')
                <div class="row border-top">
                    <div class="col-12">
                        <table cellspacing="0" width="100%">
                            <tr>
                                <td>Team:</td>
                                <td><strong>{{ $player->teamName }}</strong></td>
                            </tr>
                            <tr>
                                <td>Price:</td>
                                <td><strong>{{ $player->price }} <i class="fas fa-coins"></i></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button data-toggle="modal" data-target="#players__modal" data-player-price="{{ $player->price }}"  data-player-id="{{ $player->player_id }}" data-player-header="{{ $player->name }} {{ $player->surname }}" class="transfers__buyout btn btn-danger btn-block">Buy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
<div class="row">
    <div class="col-12">
        <div class="pagination_box">
            <nav aria-label="Page navigation">
            {{ $players->appends(request()->input())->links() }}
            </nav>
        </div>  
    </div>
</div>
<div class="players__modal">
    @include('guest.ajax.transfersBuyPlayer')
</div>