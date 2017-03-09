var locked = false;
var timer = null;

jQuery(document).ready(function () {
    initFilters();
    initTree();


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

    initFiltersBtn();
    initKinshipBtn();
    initRootsBtn();
};

var initRootsBtn = function () {
    var $rootsBtn = $('#roots-btn');
    var $rootsList = $('#roots-list');

    $rootsBtn.popover({
        trigger: "click",
        placement: "left",
        html: true,
        container: '.chart-content-pane',
        content: $rootsList
    });
};

var initKinshipBtn = function () {
    var $kinshipBtn = $('#kinship-btn');
    var $kinshipForm = $('#kinship-form');

    $kinshipBtn.popover({
        trigger: "click",
        placement: "left",
        html: true,
        container: '.chart-content-pane',
        content: $kinshipForm
    });
};

var initFiltersBtn = function () {
    var $filtersBtn = $('#filters-btn');
    var $filtersList = $('#filters-list');

    $filtersBtn.popover({
        trigger: "click",
        placement: "left",
        html: true,
        container: '.chart-content-pane',
        content: $filtersList
    });

    $filtersBtn.on('shown.bs.popover', function () {
        initFilters();
    });
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

        hideInfoCard();
        $element = $(this).next('.info-card');
        $element.addClass('show');

        url = $(this).attr('data-url');
        updateInfo(url, $element);
    });

    $(window).click(function () {
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
