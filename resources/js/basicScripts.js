import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/droppable.js';
import 'jquery-ui/ui/widgets/slider.js';

$(document).ready(function() {


    // refresh captain select based on selected squad
    function refreshCaptainSelect() {
        const selectedCaptain = $('#tactics__captain > option:selected').val();
        $('#tactics__captain > option').remove();

        $('#tactics__box > div').each(function (index) {
            if ( (index < 11) && ($(this).children().length > 0) ) {

                const playerID = $(this).find('.player__tshirt').data('player-id');
                const playerName = $(this).find('.player__tshirt').data('player-name');
                const playerNumber = $(this).find('.player__number').text();

                if(playerID == selectedCaptain) $('#tactics__captain').append(`<option selected value="${playerID}">${playerNumber}. ${playerName}</option>`)
                else $('#tactics__captain').append(`<option value="${playerID}">${playerNumber}. ${playerName}</option>`)

            }
        })

    }

    // players popup
    function onHoverShowPlayerStats ($classes) {
        $($classes).on('mouseenter', function() {
            $(this).parent().find('.player__tshirt').addClass('hovered');
            const playerID = $(this).parent().find('.player__tshirt').attr('data-player-id');
            $(`*[data-player-id="${playerID}"]`).addClass('active');
            // show names on tactic field
            $(this).parents('.empty-field').find('.player__name').fadeIn(50);
        })
        $($classes).on('mouseleave', function() {
            $(this).parent().find('.player__tshirt').removeClass('hovered');
            const playerID = $(this).parent().find('.player__tshirt').attr('data-player-id');
            $(`*[data-player-id="${playerID}"]`).removeClass('active');
            // show names on tactic field
            $(this).parents('.empty-field').find('.player__name').fadeOut(50);
        })
    }
    onHoverShowPlayerStats('.player__tshirt, .player__name');

    // count balls [temporary to check]
    // $('.player').each(function () {
    //     var balls = $(this).find('.fa-futbol').length;
    //     $(this).find('.count-balls').text(balls);
    // })

    // drag and drop 
    $('section.tactics .player__tshirt').draggable({
        revert: true,
        start: function() {
            $(this).parent().find('.player__name').detach();
        },
        stop: function() {
            const playerName = $(this).attr('data-player-name');
            $(this).parent().append(`<div class="player__name">${playerName}</div>`);
            onHoverShowPlayerStats('.player__tshirt, .player__name');
            refreshCaptainSelect();
        }
    });

    $('section.tactics *[class^="position_"], section.tactics .player').droppable({
        accept: 'section.tactics .player__tshirt',
        drop: function(event, ui) {
        if ($(this).children().length > 0) {
            let move = $(this).children().detach();
            $(ui.draggable).parent().prepend(move);
        }
            $(this).append($(ui.draggable)).addClass('remove-background');


        $('.ui-droppable').each(function () {
            if ($(this).children().length == 0) $(this).removeClass('remove-background');
        })
        }
    });


    // set formation
    $('#tactics__formation').on('change', function () {
        var $tactic_box = $('#tactics__box');
        var selectedTactic = $(this).find("option:selected").val();
        if (selectedTactic == '433') {
            $tactic_box.removeClass('f_442');
            $tactic_box.addClass('f_433');
        }
        if (selectedTactic == '442') {
            $tactic_box.removeClass('f_433');
            $tactic_box.addClass('f_442');
        }
    })

    // Tactic
    $('#tactics__save').on('click', function (e) {
        e.preventDefault();

        // squad
        let squadArray = {};
        $('*[class^="position_"]').each(function () {
            if ($(this).children().length > 0) {
                let $data = $(this).find('.player__tshirt');
                let playerID = $data.attr('data-player-id');
                let positionID = $(this).attr('data-position');
                squadArray[`pos_${positionID}`] = parseInt(playerID);
            }
        })

        // formation
        var formation = $('#tactics__formation').find("option:selected").val();
        squadArray["formation"] = formation;

        // captain
        var captain = $('#tactics__captain').find("option:selected").val();
        squadArray["captain"] = captain;

        $('input[name="tactics__json"]').val(JSON.stringify(squadArray));
        // submit
        $('.tactics__form').submit();
    })
} );