$(document).on('click', '.player__putOnTransferList', function () {
    const player_id = $(this).attr('data-player-id');
    const player_header = $(this).attr('data-player-header');
    $('#player_id').val(player_id);
    $('#playerLabel').html(player_header);
    $('#price').val('');
})

$(document).on('submit', '#confirm_sell', function (e) {
    e.preventDefault();
    const player_id = $('#player_id').val();
    let form_data = new FormData();
    let other_data = $(this).serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: `/players/putOnTransferList/${player_id}`,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data) {
            $(`.player__putOnTransferList[data-player-id=${player_id}]`).prop("disabled", true);
            $('#players__modal').modal('hide');
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
})