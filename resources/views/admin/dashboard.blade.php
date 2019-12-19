@extends('admin.menu')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            <div class="card">
                <div class="card-header">Dashboard</div>

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
        <div class="col-4">
            <div class="card">
                <div class="card-header">Add new names</div>
                <div class="card-body">
                    @if(count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif
                    @if($message = Session::get('name_added'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if($message = Session::get('name_exist'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <form class="names__form border border-light" action="{{ route('admin_addName') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="text" name="names__name" id="names__name" class="form-control mb-4" placeholder="Name">
                        <button class="btn btn-info btn-block" type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">Add new surnames</div>
                <div class="card-body">
                    @if(count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif
                    @if($message = Session::get('surname_added'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if($message = Session::get('surname_exist'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <form class="surnames__form border border-light" action="{{ route('admin_addSurname') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="text" name="names__surname" id="names__surname" class="form-control mb-4" placeholder="Surname">
                        <button class="btn btn-info btn-block" type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 py-4">
            <div class="card">
                <div class="card-header">Add new formation</div>
                <div class="card-body">
                    <div class="tactics">
                        <div id="tactics__box" class="tactics__field f_442">
                            @for ($i = 1; $i <= 11; $i++)
                            <div data-position="{{ $i }}" class="position_{{ $i }} empty-field">
                            </div>
                            @endfor
                        </div>
                        <form class="tactics__form border border-light" action="{{ route('user_saveTactic') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="tactics__json">
                        </form>
                        <button id="tactics__save" class="btn btn-info btn-block">Save tactic</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
