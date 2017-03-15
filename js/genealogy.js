var locked = false;
var timer = null;

jQuery(document).ready(function () {
    initFilters();
    initTree();

    initTimeline();

    // Scroll to the tree div
    if ($('.tree-container').length) {
        $('html, body').animate({
            scrollTop: $('.tree-container').offset().top
        }, 'slow');
    }

//    initIntro();
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

var centerNode = function ($node) {
    var $container = $('.chart-content-pane');
    var offset = {
        top: ($container.height() - $node.height()) / 2,
        left: ($container.width() - $node.width()) / 2
    };

    $node.ScrollTo({
        duration: 1000,
        durationMode: 'all',
        offsetTop: offset.top,
        offsetLeft: offset.left
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

    $('#info-card').load(ajaxUrl, function () {
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

    centerNode($('.node').first());

    initTreeNav();
    initTimelineNav();

    initKinshipDropdown();
    initSearchTree();
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

var initIntro = function () {
    $firstNade = $('.node').first();
    $firstNade.attr('data-intro', 'اضغط على الاسم للحصول على معلومات إضافية عن هذا الشخص: كتاريخ الميلاد والوفاة، اسم الأب والأم والزوج،  واحضائيات عن ذريته.<br />ومن خلال هذه القائمة يمكنك الضغط على ايقونة الملاحظات <i class="fa fa-comment" aria-hidden="true"></i> لتزويدنا بأي ملاحظات أو معلومات إضافية عن هذه الشخص');
    $firstNade.attr('data-step', '1');
    $firstNade.attr('data-position', 'left');

    introJs()
            .setOptions(introOpts)
            .onbeforechange(function (targetElement) {
                console.log('Before: ' + this._currentStep);
                switch (this._currentStep) {
                    case 1:
                        break;

                    case 3:
//                        $(targetElement).find('a').first().trigger('click');
                        break;


                    default:

                        break;
                }
            })
            .onchange(function (targetElement) {
                console.log('Step: ' + this._currentStep);
                switch (this._currentStep) {
                    case 0:
                        $(targetElement).find('a').first().trigger('click');
                        break;

                    default:

                        break;
                }
            })
            .start();
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

    url = $source.data('url');
    updateInfo(url);

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