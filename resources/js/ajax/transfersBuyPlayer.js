$(document).on('click', '.transfers__buyout', function () {
    const player_id = $(this).attr('data-player-id');
    const player_header = $(this).attr('data-player-header');
    const player_price = $(this).attr('data-player-price');
    $('#player_id').val(player_id);
    $('#playerLabel').html(player_header);
    $('#price').html(player_price);
})

$(document).on('submit', '#confirm_buy', function (e) {
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
        url: `/transfers/buyPlayer/${player_id}`,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data) {
            $('#players__modal').modal('hide');
            // refresh ajax filters
            $('form.transfers__filters__form').submit();
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
})