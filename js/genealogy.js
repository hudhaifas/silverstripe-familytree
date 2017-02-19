var locked = false;

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
        kinship = $(this).attr('data-kinship');
        $(this).jOrgChart({
            chartElement: '#' + kinship,
//            depth: 3
//            direction: dir
        });
//        $('#' + kinship).dragScroll({});

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
    $('#timeline-input').bootstrapSlider({
        ticks: ['1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', '2017'],
        ticks_labels: ['1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', '2017'],
        tooltip: 'always',
    }).on("slideStop", function (evt) {
        updateTimePoint(evt.value);
    });

    $('#ex20a').on('click', function (e) {
        e.preventDefault();
        $('#ex20a')
                .parent()
                .find(' >.well')
                .toggle()
                .find('input')
                .bootstrapSlider('relayout');
    });
};

var updateTimePeriod = function (start, end) {
    $('.node').fadeTo("slow", 0.3);

    $('.node').filter(function () {
        isBorn = $(this).data('birth') <= end;
        isLive = $(this).data('death') >= start;// || !$(this).data('death');

        return isBorn && isLive;

    }).fadeTo("slow", 1);

//    $('.node').fadeTo("slow", 0.5);
////    $('.node').hide();
//    console.log('Fading...');
//    $('.node').filter(function () {
//        return $(this).data('birth') >= start || $(this).data('death') >= end;
//    }).fadeTo("slow", 1);

};

var updateTimePoint = function (time) {
    $('.node').fadeTo("fast", 0.25);

    var $born = $('.node').filter(function () {
        isBorn = $(this).data('birth') <= time && $(this).data('birth');
        return isBorn;
    });

    var $aliveRow = $born.closest("tr");
    $born.css('cursor', 'zoom-out');
    $aliveRow.removeClass('contracted').addClass('expanded');
    $aliveRow.nextAll("tr").css('visibility', '');
    $aliveRow.nextAll("tr").css('display', '');

    var $notBorb = $('.node').filter(function () {
        isBorn = $(this).data('birth') <= time;
        return !isBorn;
    });

    var $notAliveRow = $notBorb.closest("tr");
    $notBorb.css('cursor', 'zoom-in');
    $notAliveRow.removeClass('expanded').addClass('contracted');
    $notAliveRow.nextAll("tr").css('visibility', 'hidden');
    $notAliveRow.nextAll("tr").css('display', 'none');

    $('.node').filter(function () {
        isBorn = $(this).data('birth') <= time && $(this).data('birth');
        isLive = $(this).data('death') >= time || !$(this).data('death');

        return isBorn && isLive;

    }).fadeTo("fast", 1);

    $('.node').filter(function () {
        isBorn = !$(this).data('birth');

        return isBorn;

    }).addClass("gray-node");

//    $('.node').hide();
//
//    $('.node').filter(function () {
//        isBorn = $(this).data('birth') <= time && $(this).data('birth');
//        isLive = $(this).data('death') >= time || !$(this).data('death');
//
//        return isBorn && isLive;
//
//    }).show();
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