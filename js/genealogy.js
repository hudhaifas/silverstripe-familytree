var locked = false;
var timer = null;

jQuery(document).ready(function () {
    initTree();

    initFilters();

    initTimeline();
//    updateTimePeriod(1980, 2017);
//    updateTimePoint(1979);

    // Scroll to the tree div
    if ($('.tree-container').length) {
        $('html, body').animate({
            scrollTop: $('.tree-container').offset().top
        }, 'slow');
    }
});

var showPerson = function (url) {
    url = appendFilters(url);

    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;
    var cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('#tree-loader').show();
    $('#genealogy-tree').html('');

    lockLinks();
    $('#tree-holder').load(ajaxUrl, function () {
        initTree();
        initTimeline();

        window.history.pushState(
                {url: cleanUrl},
                document.title,
                cleanUrl
                );

        $('#tree-loader').hide();

        releaseLinks();
    });
};

/**
 * 
 * @param {type} url
 * @returns {undefined}
 */
var updateInfo = function (url) {
    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;

    $('#info-loader').show();
    $('#info-body').html('');
    lockLinks();

    $("#panel-info").load(ajaxUrl, function () {
        registerLinks();
        $('#info-loader').hide();
        $('#collapse-info-btn').trigger('click');

        releaseLinks();
    });
};

var initTree = function () {
    $kinships = $('.genealogy-kinship');
    dir = $kinships.length > 1 ? 'l2r' : 't2b';

    $kinships.each(function () {
        kinship = $(this).data('kinship');
        multiple = $(this).data('multiple');

        $(this).jOrgChart({
            chartElement: '#' + kinship,
            multipleRoot: multiple, // Support multiple roots tree
//            depth: 3
//            direction: dir
        });
    });

    registerLinks();

    window.onpopstate = function (e) {
        console.log('e: ' + e);
        if (e.state.url) {
            console.log('onpopstate: ' + e.state.url);
            showPerson(e.state.url);
        } else {
            e.preventDefault();
        }
    };
};

var initTimeline = function () {
    if (!$("#timeline-input").length) {
        return;
    }

    var startYear = 1900;
    var currentYear = new Date().getFullYear();

    if ($(window).width() < 800) {
        ticks = ['1900', '1950', '2000', currentYear];
        ticks_labels = ['1900', '1950', '2000', currentYear];
    } else if ($(window).width() < 1200) {
        ticks = ['1900', '1920', '1940', '1960', '1980', '2000', currentYear];
        ticks_labels = ['1900', '1920', '1940', '1960', '1980', '2000', currentYear];
    } else {
        ticks = ['1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', currentYear];
        ticks_labels = ['1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', currentYear];
    }

    $('#timeline-input').bootstrapSlider({
        ticks: ticks,
        ticks_labels: ticks_labels,
        tooltip: 'always',
    }).on("slideStop", function (evt) {
        updateTimePoint(evt.value);
    });

    $('#timeline-btn').on('click', function (e) {
        e.preventDefault();

        $extraPane = $('#chart-extra');
        $extraPane.toggle();

        $well = $('#timeline-btn')
                .parent()
                .find(' >.well');

        $well.toggle();

        $slider = $well.find('input');

        $slider.bootstrapSlider('relayout');

        if ($well.is(":visible")) {
            $slider.bootstrapSlider('setValue', 1900);
//            $slider.bootstrapSlider('relayout');
            updateTimePoint(1900);

            initTimelineControl($slider, currentYear);

        } else {
            resetTimeline();
            destroyTimer();
            $('#timeline-control').unbind('click');
        }
    });
};

var initTimelineControl = function ($slider, maxValue) {
    timer = null;
    var interval = 200;

    $('#timeline-control').on('click', function (e) {
        e.preventDefault();
        var value = $slider.bootstrapSlider('getValue');

        if ($(this).find('i').hasClass('fa-play-circle')) {
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
            destroyTimer();
        }
    });
};

var destroyTimer = function () {
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
    $('.node').addClass('timeline-node');

    var $born = $('.node').filter(function () {
        isDead = $(this).data('birth') <= time && $(this).data('birth');
        return isDead;
    });
    var $notBorb = $('.node').filter(function () {
        isDead = $(this).data('birth') <= time;
        return !isDead;
    });
    var $isDead = $('.node').filter(function () {
        isDead = $(this).data('death') <= time && $(this).data('death');
        return isDead;
    });

    $isDead.addClass('transparent-node');

    var $aliveRow = $born.closest("tr");
    $born.css('cursor', 'zoom-out');
    $aliveRow.removeClass('contracted').addClass('expanded');
    $aliveRow.nextAll("tr").css('visibility', '');
    $aliveRow.nextAll("tr").css('display', '');

    $notBorb.addClass('empty-node');

    var $notAliveRow = $notBorb.closest("tr");
    $notBorb.css('cursor', 'zoom-in');
    $notAliveRow.removeClass('expanded').addClass('contracted');
    $notAliveRow.nextAll("tr").css('visibility', 'hidden');
    $notAliveRow.nextAll("tr").css('display', 'none');

    var aliveCount = $aliveRow.length;
    var deadCount = $isDead.length;
    $extraPane = $('#chart-extra');
    $extraPane.html('<span>' + (aliveCount - deadCount) + '</span>');
//    $('.node').filter(function () {
//        isBorn = $(this).data('birth') <= time && $(this).data('birth');
//        isLive = $(this).data('death') >= time || !$(this).data('death');
//
//        return isBorn && isLive;
//
//    }).removeClass('empty-node');
    $born.removeClass('empty-node');

    $('.node').filter(function () {
        isDead = !$(this).data('birth');

        return isDead;

    }).addClass("gray-node");
};

var resetTimeline = function () {
    $('.node').removeClass('timeline-node empty-node gray-node transparent-node');
};

var initFilters = function () {
    url = $(location).attr('href');
    var uri = URI(url);

    var params = uri.search(true);
    if (!params['m']) {
        params['m'] = 1;
    }

    if (!params['ms']) {
        params['ms'] = 1;
    }


    $('input.options-check').each(function () {
        kinship = $(this).attr('id');
        status = params[kinship];
        $(this).prop('checked', status == 1 ? true : false);
    });

};

var appendFilters = function (url) {
    var uri = URI(url);

    var params = {};
    $('input[type=checkbox].options-check').each(function () {
        kinship = $(this).attr('id');
        value = this.checked ? 1 : 0;

        params[kinship] = value;
    });
    uri.setSearch(params);

    return uri.toString();
};

var lockLinks = function () {
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', true);
    locked = true;
};

var releaseLinks = function () {
    locked = false;
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', false);
};

var unregisterLinks = function () {
    $("#toggle-fullscreen, #toggle-nodes, #export-tree, input[type=checkbox]").unbind("click");
};

var registerLinks = function () {
    unregisterLinks();

    $("a.info-item").click(function (event) {
        event.preventDefault();

        if (locked) {
            return;
        }

        url = $(this).attr('data-url');
        updateInfo(url);
    });

    $("a.options-item").click(function (event) {
        event.preventDefault();

        if (locked) {
            return;
        }

        url = $(this).attr('href');
        showPerson(url);
    });

    // Kinship form action
    $("#Form_Form_Kinship_action_findKinship").click(function (event) {
        event.preventDefault();

        if (locked) {
            return;
        }

        p1 = $("[name='Person1']").val();
        p2 = $("[name='Person2']").val();

        var uri = URI(url);
        uri.segment(2, p1);
        uri.segment(3, p2);

        showPerson(uri.toString());
    });

    $('input[type=checkbox]#f').change(function () {
        event.preventDefault();
        if (!this.checked) {
            $('input#fs').prop('checked', false);
        }
    });

    $('input[type=checkbox]#fs').change(function () {
        event.preventDefault();
        if (this.checked) {
            $('input#f').prop('checked', true);
        }
    });

    $('input[type=checkbox]#m').change(function () {
        event.preventDefault();
        if (!this.checked) {
            $('input#ms').prop('checked', false);
        }
    });

    $('input[type=checkbox]#ms').change(function () {
        event.preventDefault();
        if (this.checked) {
            $('input#m').prop('checked', true);
        }
    });

    $('input.options-check').change(function () {
        event.preventDefault();
        if (locked) {
            return;
        }

        url = $(location).attr('href');
        showPerson(url);
    });
};