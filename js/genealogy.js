var locked = false;
var timer = null;

jQuery(document).ready(function () {
    initFilters();
    initTree();


    initTimeline();
//    updateTimePeriod(1980, 2017);
//    updateTimePoint(1979);

    initKinshipDropdown();

    // Scroll to the tree div
    if ($('.tree-container').length) {
        $('html, body').animate({
            scrollTop: $('.tree-container').offset().top
        }, 'slow');
    }

    $('.node').first().attr('data-intro', 'Click on the name to see more details');
    $('.node').first().attr('data-step', '1');
    $('.node').first().attr('data-position', 'left');

//    $('.node').first().find('a').attr('data-intro', 'Click on the name to see more details');


    introJs().start();

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

        initFilters();
        releaseLinks();
    });
};

/**
 * 
 * @param {type} url
 * @returns {undefined}
 */
var updateInfo = function (url, $element) {
    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;

    $('#info-loader').show();
    $('#info-body').html('');
    lockLinks();

    $element.load(ajaxUrl, function () {
        registerLinks();
        $('#info-loader').hide();
//        $('#collapse-info-btn').trigger('click');

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
//            dragScroller: false,
//            zoom: false
//            depth: 3
//            direction: dir
        });

//        $('#' + kinship + ' .chart-pane').panzoom({
//            minScale: 1
//        });
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

    initTreeNav();
    initTimelineNav();
};

var initTreeNav = function () {
    $controls = $('<div class="chart-controls-hor"></div>');
    $treeMenu = $('#menu-nav');
    $controls.append($treeMenu);
    $('#k1').append($controls);
};

var initTimelineNav = function () {
    $controls = $('<div class="chart-controls-bottom"></div>');
    $treeMenu = $('#timeline-nav');
    $controls.append($treeMenu);
    $('#k1').append($controls);
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

var hideInfoCard = function () {
    $('.info-card').html('');
    $('.info-card').removeClass('show');
//    $('.info-card').hide();

};

var showInfoCard = function (src) {
    hideInfoCard();

    var minScreenWidth = 760;
    $source = $(src);
    $element = $('#info-card');

    url = $source.attr('data-url');
    updateInfo(url, $element);

    $node = $source.parent();

    if ($(window).width() > minScreenWidth && $node.hasClass('node')) {
        var errorMargin = 36;
        var hMargin = 10;
        var wMargin = 8;

        $container = $('#k1');
        cWidth = $container.width();
        cHeight = $container.height();

        nWidth = 64;
        nHeight = 30;

        cardWidth = 420;
        cardHeight = 220;

        var rect = $node.position();

        var top = rect.top;
        var left = rect.left;

        coord = {};

        if (left + cardWidth > cWidth - errorMargin) {
            coord['left'] = 'initial';
            coord['right'] = cWidth - left - nWidth + wMargin;
        } else {
            coord['right'] = 'initial';
            coord['left'] = left + 2;
        }

        if (top + cardHeight > cHeight - hMargin) {
            coord['top'] = 'initial';
            coord['bottom'] = cHeight - top - nHeight + hMargin;
        } else {
            coord['bottom'] = 'initial';
            coord['top'] = top;
        }

        $element.css(coord);
    }

    $element.addClass('show');
};

var registerLinks = function () {
    unregisterLinks();

//    $('a.info-item').on('mousedown touchstart', function (e) {
//        e.stopImmediatePropagation();
//    });

    $("a.info-item").click(function (event) {
        event.preventDefault();

        if (locked) {
            return;
        }

        showInfoCard(this);
    });

    $(window).click(function () {
        if (locked) {
            return;
        }

        hideInfoCard();
    });

    $('#close-card').click(function () {
        event.preventDefault();

        if (locked) {
            return;
        }

        hideInfoCard();
    });

    $('.info-card').click(function (event) {
        event.stopPropagation();
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

    $('input[type=checkbox]#f').change(function (event) {
        event.preventDefault();
        if (!this.checked) {
            $('input#fs').prop('checked', false);
        }
    });

    $('input[type=checkbox]#fs').change(function (event) {
        event.preventDefault();
        if (this.checked) {
            $('input#f').prop('checked', true);
        }
    });

    $('input[type=checkbox]#m').change(function (event) {
        event.preventDefault();
        if (!this.checked) {
            $('input#ms').prop('checked', false);
        }
    });

    $('input[type=checkbox]#ms').change(function (event) {
        event.preventDefault();
        if (this.checked) {
            $('input#m').prop('checked', true);
        }
    });

    $('input.options-check').change(function (event) {
        event.preventDefault();
        if (locked) {
            return;
        }

        url = $(location).attr('href');
        showPerson(url);
    });
};
