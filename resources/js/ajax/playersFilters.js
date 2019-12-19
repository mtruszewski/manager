$('.players__filters__form input, .players__filters__form select').on('change keyup', function(){
    $(this).parents('form').trigger('submit');
})

$('form.players__filters__form').on('submit', function(e) {
    // e.preventDefault();
    let form_data = new FormData();
    let other_data = $(this).serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });

    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/players/filters',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data) {
                $('.players__ajaxFilters').html(data);
                new Vue({
                    el: '.balls',
                });
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
     });
    return false;
})

$(document).ajaxComplete(function() {
    $('.players__ajaxFilters .pagination li a').click(function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'post',
            success: function(data) {
                $('.players__ajaxFilters').html(data);
                new Vue({
                    el: '.balls',
                });
            }
        });
    });
});