var locked = false;
var timer = null;

jQuery(document).ready(function () {
    // Scroll to the tree div
    if ($('.tree-container').length) {
        $('html, body').animate({
            scrollTop: $('.tree-container').offset().top
        }, 'slow').promise().then(function () {
            // Animation complete
            initFilters();
            showPerson();
        });
    }

//    initIntro();
});

var showPerson = function (url) {
    if (url === undefined) {
        url = $(location).attr('href');
    }
    url = appendFilters(url);


    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;
    var cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('#chart-loader').show();
    $('#genealogy-tree').html('');

    lockAll();
    $('#tree-container').load(ajaxUrl, function () {
        initTree();

        window.history.pushState(
                {url: cleanUrl},
                document.title,
                cleanUrl
                );

        $('#chart-loader').hide();

        initFilters();
        unlockAll();
    });
};

var initTree = function () {
    var $kinship = $('#chart-list');
    var multiroot = $kinship.data('multiroot');
    var collapsible = $kinship.data('collapsible');

    $kinship.jOrgChart({
        chartElement: '#chart-container',
        // Support multiple roots tree
        multipleRoot: multiroot,
        // Fullscree options
        fullscreenOnBtn: $('#fullscreen-in-btn'),
        fullscreenOffBtn: $('#fullscreen-out-btn'),
        // Zoom options
        zoomInBtn: $('#zoom-in-btn'),
        zoomOneBtn: $('#zoom-one-btn'),
        zoomOutBtn: $('#zoom-out-btn'),
        // Export options
        exportBtn: $('#dwonload-btn'),
        // Collapse options
        collapsible: collapsible,
        collapseBtn: $('#collapse-btn'),
        expandBtn: $('#expand-btn'),
//            dragScroller: false,
//            zoom: false
//            depth: 3
//            direction: dir
    });

//    $('#chart-list .chart-pane').panzoom({
//        minScale: 1
//    });

    initAllControls();
    bindAll();
    initTimeline();

    centerNode($('.node').first());
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
        var id = $(this).attr('id');
        var status = params[id];
        $(this).prop('checked', status == 1 ? true : false);
    });
};

var initIntro = function () {
    var $firstNade = $('.node').first();
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
        var id = $(this).attr('id');
        var value = this.checked ? 1 : 0;

        params[id] = value;
    });
    uri.setSearch(params);

    return uri.toString();
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

var showInfoCard = function (src) {
    hideInfoCard();

    var minScreenWidth = 760;
    var $source = $(src);
    var $element = $('#info-card');

    var url = $source.data('url');
    updateInfo(url);

    var $node = $source.parent();

    if ($(window).width() > minScreenWidth && $node.hasClass('node')) {
        var errorMargin = 36;
        var hMargin = 10;
        var wMargin = 8;

        var $container = $('#chart-container');
        var cWidth = $container.width();
        var cHeight = $container.height();

        var nWidth = 64;
        var nHeight = 30;

        var cardWidth = 500;
        var cardHeight = 280;

        var rect = $node.position();

        var top = rect.top;
        var left = rect.left;

        var coord = {};

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

var hideInfoCard = function () {
    $('.info-card').html('');
    $('.info-card').removeClass('show');
//    $('.info-card').hide();

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
    lockAll();

    $('#info-card').load(ajaxUrl, function () {
        bindAll();
        $('#info-loader').hide();
//        $('#collapse-info-btn').trigger('click');

        unlockAll();
    });
};

var loadModal = function (url) {
    $.get(url, function (html) {
//            console.log(html);
        var $content = $(html);
        $content.appendTo('body').modal({
            body: '#tree-container',
            closeText: '<span aria-hidden="true">×</span>',
            closeClass: 'close',
            fadeDuration: 400
        });

        $content.on($.modal.BEFORE_OPEN, function (event, modal) {
            $('.ajax-modal-nested').click(function (event) {
                event.preventDefault();
                loadModal(this.href);
            });
            $("body").addClass("modal-open");
        });

        $content.on($.modal.AFTER_CLOSE, function (event, modal) {
            $content.remove();
            $("body").removeClass("modal-open")
        });

        rebindAutocomplete();
    });
};