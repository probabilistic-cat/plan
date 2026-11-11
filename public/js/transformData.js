$(() => {
    transformMinutesToWDHS();
});

function transformMinutesToWDHS() {
    $('.time-minutes').each(function(){
        const minutes = parseInt($(this).html());
        const timeWDHS = getTimeWDHM(minutes);
        $(this).html(timeWDHS);
    });
}

function getTimeWDHM(minutes) {
    const hourMinutes = 60;
    const dayMinutes = 8 * hourMinutes;
    const weekMinutes = 5 * dayMinutes;

    let time = '';

    if (minutes < 0) {
        minutes = 0;
    }

    const weeks = Math.trunc(minutes / weekMinutes);
    if (weeks > 0) {
        time += weeks + 'w ';
        minutes = minutes - weeks * weekMinutes;
    }

    const days = Math.trunc(minutes / dayMinutes);
    if (days > 0) {
        time += days + 'd ';
        minutes = minutes - days * dayMinutes;
    }

    const hours = Math.trunc(minutes / hourMinutes);
    if (hours > 0) {
        time += hours + 'h ';
        minutes = minutes - hours * hourMinutes;
    }

    if (minutes > 0 || time === '') {
        time += minutes + 'm';
    }

    return time.trimEnd();
}
