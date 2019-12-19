$('.transfers__filters__form input, .transfers__filters__form select').on('change keyup', function(){
    $(this).parents('form').trigger('submit');
})

$('form.transfers__filters__form').on('submit', function(e) {
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
            url: '/transfers/filters',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data) {
                $('.transfers__ajaxFilters').html(data);
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
    $('.transfers__ajaxFilters .pagination li a').click(function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'post',
            success: function(data) {
                $('.transfers__ajaxFilters').html(data);
                new Vue({
                    el: '.balls',
                });
            }
        });
    });
});