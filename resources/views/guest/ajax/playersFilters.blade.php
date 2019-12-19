<div class="row balls">
@foreach($players as $player)
    <div class="player col-6 pb-4">
        <div class="card">
            <div class="card-header">
                <h4>{{ $player->number }}. {{ $player->name }} {{ $player->surname }}</h4>
            </div>
            <div class="card-body">
            <button @if ($player->tl) disabled @endif 
                class="player__putOnTransferList btn btn-outline-info" 
                data-player-header="{{ $player->number }}. {{ $player->name }} {{ $player->surname }}" 
                data-player-id="{{ $player->id }}" 
                data-toggle="modal" 
                data-target="#players__modal"><i class="fas fa-money-bill-wave" aria-hidden="true"></i></button>
                @include('card.player')
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
        