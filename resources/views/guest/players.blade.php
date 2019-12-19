@extends('guest.menu')

@section('content')
<section class="players">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="players__filters">
                    <div class="card">
                        <div class="card-header">
                            <h2>Filters</h2>
                        </div>
                        <div class="card-body">
                            <form class="players__filters__form border border-light" action="{{ route('user_players') }}" method="get" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="players__order">Order by</label>
                                        <select name="filter[orderBy]" id="players__order" class="form-control">
                                            <option value="number">Number</option>
                                            @foreach ($attributes as $attr)
                                            <option value="{{ $attr }}">{!! ucfirst(trans(str_replace('_', ' ', $attr))) !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="players__sort">Sort by</label>
                                        <select name="filter[sortBy]" id="players__sort" class="form-control">
                                            <option value="asc">ASC</option>
                                            <option value="desc">DESC</option>
                                        </select>
                                    </div>
                                    @foreach ($attributes as $attr)
                                    <div class="form-group col-12 players__rangeFilters">
                                        <label for="filter[{{ $attr }}][0]">{!! str_replace('_', ' ', $attr) !!}:</label>
                                        <div class="input-group align-items-center">
                                            <input type="text" id="filter[{{ $attr }}][0]" name="filter[{{ $attr }}][0]" class="col-md-2 form-control" pattern="[0-9]*" min="0" max="10" maxlength="2">
                                            <div id="slider-range-{{ $attr }}" class="col-md-8 form-control"></div>
                                            <input type="text" id="filter[{{ $attr }}][1]" name="filter[{{ $attr }}][1]" class="col-md-2 form-control" pattern="[0-9]*" min="0" max="10" maxlength="2">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button class="btn btn-info btn-block d-none" type="submit">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="players__ajaxFilters">
                    @include('guest.ajax.playersFilters')
                </div>
            </div>
        </div>
    </div>
    <div class="players__modal">
        @include('guest.ajax.playerPutOnTransferList')
    </div>
</section>
@endsection