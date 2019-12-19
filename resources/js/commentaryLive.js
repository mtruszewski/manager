function firstHalfTime(timer, $commentaryList, $commentaryTimer) {
    let firstHalf = setInterval(function () {
        timer++;

        $commentaryTimer.html(`${timer}'`);
        $commentaryList.find(`*[data-commentary-minute="${timer}"]`).fadeIn(100);

        if (timer == 45) {
            clearInterval(firstHalf);
            countAddedTime(timer, $commentaryList, $commentaryTimer);
        }

    }, 1000)

    return 45;
}

function secondHalfTime(timer, $commentaryList, $commentaryTimer) {
    let secondHalf = setInterval(function () {
        timer++;

        $commentaryTimer.html(`${timer}'`);
        $commentaryList.find(`*[data-commentary-minute="${timer}"]`).fadeIn(100);

        if (timer == 90) {
            clearInterval(secondHalf);
            countAddedTime(timer, $commentaryList, $commentaryTimer);
        }
    }, 1000)

    return 90;
}

function countAddedTime(timer, $commentaryList, $commentaryTimer){
    let addedTime = 0;
    const timerAdded = setInterval(function () {
        ++addedTime;
        $commentaryList.find(`*[data-commentary-minute="${timer}+${addedTime}"]`).fadeIn(100);
        $commentaryTimer.html(`${timer}+${addedTime}'`);
        if (addedTime == 5) {
            clearInterval(timerAdded);
        }
    }, 1000);
}

function matchTimer() {
    const $commentaryList = $('.commentary__list');
    const $commentaryTimer = $('.commentary__timer');
    let timer = -1;

    let fh = firstHalfTime(timer, $commentaryList, $commentaryTimer);

    setTimeout(function () {
        let sh = secondHalfTime(fh, $commentaryList, $commentaryTimer);
    }, 45000+6000)

}

function scrollToCommentary() {
    $('html, body').animate({
        scrollTop: $(".commentary").offset().top
    }, 2000);
}

$(document).ready(function() {
    $('button.live').on('click', function () {

        $(this).fadeOut();

        scrollToCommentary();

        matchTimer();
        $('.commentary__timer').fadeIn();
    });

    $('button.result').on('click', function () {

        $('.challenge__buttons, .commentary__timer').fadeOut();

        scrollToCommentary();

        matchTimer();

        $('.commentary__list > li').each(function () {
            $(this).fadeIn();
        })

    });
});