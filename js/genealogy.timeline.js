/*
 * 2-28-2017
 */

var initTimeline = function () {
    if (!$("#timeline-input").length) {
        $('.no-timeline').hide();
        return;
    }

    var ticks = initTimelineTicks();

    if (ticks == null) {
        $('.no-timeline').hide();
        return;
    }

    $('#timeline-input').bootstrapSlider({
        ticks: ticks['ticks'],
        ticks_labels: ticks['labels'],
        tooltip: 'always',
    }).on("slideStop", function (evt) {
        updateTimePoint(evt.value);
    });

    $('#timeline-btn').on('click', function (event) {
        event.preventDefault();

        $extraPane = $('#chart-extra');
        $extraPane.toggleClass('open');

        $well = $('.chart-controls-bottom');

        $well.toggleClass('open');

        $(this).parent().toggleClass('pressed');

        if ($well.hasClass("open")) {
            $slider = $well.find('input');
            $slider.bootstrapSlider('relayout');
            $slider.bootstrapSlider('setValue', ticks['start']);
//            $slider.bootstrapSlider('relayout');
            updateTimePoint(ticks['start']);

            initTimelineControl($slider, ticks['end']);
            $('.hide-on-timeline').hide();

        } else {
            resetTimeline();
            destroyTimer();
            $('#timeline-control').unbind('click');
            $('.hide-on-timeline').show();
        }
    });
};

var initTimelineControl = function ($slider, maxValue) {
    timer = null;
    var interval = 200;

//    console.log('Init Controls');

    $('#timeline-control').on('click', function (e) {
        e.preventDefault();
        var value = $slider.bootstrapSlider('getValue');

        if ($(this).find('i').hasClass('fa-play-circle')) {

//            console.log('Play');

            timer = setInterval(function () {
                value = value + 1;

                $slider.bootstrapSlider('setValue', value);
                updateTimePoint(value);

                if ((value) >= (maxValue)) {
                    $('#timeline-control').trigger('click');
                }

            }, interval);

            $('#timeline-control').find('.fa-play-circle').addClass('fa-pause-circle').removeClass('fa-play-circle');

        } else {
//            console.log('Pause');
            destroyTimer();
        }
    });
};

var initTimelineTicks = function () {
    var $nodes = $('.node').filter(function () {
        return $(this).data('birth');
    });

    if ($nodes.length <= 1) {
        return null;
    }

    var $node = $nodes.first();

    var minYear = $node.data('birth');
    var maxYear = new Date().getFullYear();

    var startYear = Math.floor(minYear / 10) * 10;

    var tickPeriod = maxYear - minYear;

    if ($(window).width() < 480) {
        tickCount = 3;
    } else if ($(window).width() < 760) {
        tickCount = 4;
    } else if ($(window).width() < 1200) {
        tickCount = 7;
    } else {
        tickCount = 9;
    }

    var tickStep = tickPeriod / tickCount;
    if (tickStep > 10) {
        tickStep = Math.round(tickStep / 10) * 10;
    } else {
        tickStep = Math.round(tickStep);
    }

    if (tickStep < 1) {
        return null;
    }

    var ticks = [];

    for (var year = startYear; year < maxYear; year += tickStep) {
        ticks.push(year);
    }
    ticks.push(maxYear);

    return {
        start: startYear,
        end: maxYear,
        ticks: ticks,
        labels: ticks
    };

};

var resetTimeline = function () {
    $('.node').removeClass('timeline-node empty-node gray-node transparent-node');
};

var destroyTimer = function () {
//    console.log('Destroy');

    clearInterval(timer);
    timer = null;

    $('#timeline-control').find('.fa-pause-circle').addClass('fa-play-circle').removeClass('fa-pause-circle');
};

var updateTimePeriod = function (start, end) {
    $('.node').fadeTo("slow", 0.3);

    $('.node').filter(function () {
        isDead = $(this).data('birth') <= end;
        isLive = $(this).data('death') >= start;// || !$(this).data('death');

        return isDead && isLive;

    }).fadeTo("slow", 1);

//    $('.node').fadeTo("slow", 0.5);
////    $('.node').hide();
//    console.log('Fading...');
//    $('.node').filter(function () {
//        return $(this).data('birth') >= start || $(this).data('death') >= end;
//    }).fadeTo("slow", 1);

};

var updateTimePoint = function (time) {
    resetTimeline();
    $('.node').not( ".clan" ).addClass('timeline-node');

    var $born = $('.node').filter(function () {
        isDead = $(this).data('birth') <= time && $(this).data('birth');
        return isDead;
    });
    var $notBorb = $('.node').not( ".clan" ).filter(function () {
        isDead = $(this).data('birth') <= time;
        return !isDead;
    });
    var $isDead = $('.node').not( ".clan" ).filter(function () {
        isDead = $(this).data('death') <= time && $(this).data('death');
        return isDead;
    });

    $isDead.addClass('transparent-node');

    var $aliveRow = $born.closest("tr");
    $aliveRow.removeClass('contracted').addClass('expanded');
    $aliveRow.nextAll("tr").css('visibility', '');
    $aliveRow.nextAll("tr").css('display', '');

    $notBorb.addClass('empty-node');

    var $notAliveRow = $notBorb.closest("tr");
    $notAliveRow.removeClass('expanded').addClass('contracted');
    $notAliveRow.nextAll("tr").css('visibility', 'hidden');
    $notAliveRow.nextAll("tr").css('display', 'none');

    var aliveCount = $aliveRow.length;
    var deadCount = $isDead.length;
    $extraPane = $('#chart-extra');
    $extraPane.html('<span>' + (time) + ' : ' + (aliveCount - deadCount) + '</span>');
//    $('.node').filter(function () {
//        isBorn = $(this).data('birth') <= time && $(this).data('birth');
//        isLive = $(this).data('death') >= time || !$(this).data('death');
//
//        return isBorn && isLive;
//
//    }).removeClass('empty-node');
    $born.removeClass('empty-node');

    $('.node').not( ".clan" ).filter(function () {
        isDead = !$(this).data('birth');

        return isDead;

    }).addClass("gray-node");
};

