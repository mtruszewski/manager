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
                <form id="confirm_sell" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="price" class="col-form-label">Price:</label>
                    <input type="number" class="form-control" name="price" id="price">
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