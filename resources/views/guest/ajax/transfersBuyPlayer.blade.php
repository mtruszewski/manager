@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="modal fade" id="players__modal" tabindex="-1" role="dialog" aria-labelledby="playerLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="playerLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="confirm_buy" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="">Do you confirm buying player for <span id="price"></span><i class="fas fa-coins"></i></strong>?</div>
                    <input type="hidden" class="form-control" name="player_id" id="player_id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>