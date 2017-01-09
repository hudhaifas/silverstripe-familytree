var locked = false;

jQuery(document).ready(function () {
    initTree();

    initFilters();

    // Scroll to the tree div
    $('html, body').animate({
        scrollTop: $('.tree-container').offset().top
    }, 'slow');
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
        $('#' + kinship).dragScroll({});

        centerTree('#' + kinship + ' table', '#' + kinship);
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

var toggleAllNodes = function () {
    $nodeDiv = $('div.node');
    var $tr = $nodeDiv.closest("tr");

    if ($tr.hasClass('contracted')) {
        $nodeDiv.css('cursor', 'zoom-out');
        $tr.removeClass('contracted').addClass('expanded');
        $tr.nextAll("tr").css('visibility', '');
        $tr.nextAll("tr").css('display', '');

        return true;
    } else {
        $nodeDiv.css('cursor', 'zoom-in');
        $tr.removeClass('expanded').addClass('contracted');
        $tr.nextAll("tr").css('visibility', 'hidden');
        $tr.nextAll("tr").css('display', 'none');

        return false;
    }
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
        uri.segment(1, p1);
        uri.segment(2, p2);

        showPerson(uri.toString());
    });

    // Full-screen link
    $('#toggle-fullscreen').on('click', function () {
        event.preventDefault();
        if (locked) {
            return;
        }

        $('.tree-container').toggleFullScreen();

    });

    // Collapse/Expand link
    $('#toggle-nodes').on('click', function () {
        event.preventDefault();
        if (locked) {
            return;
        }

        if (toggleAllNodes()) {
            $(this).find('.fa').removeClass('fa-expand').addClass('fa-compress');
        } else {
            $(this).find('.fa').removeClass('fa-compress').addClass('fa-expand');
        }
    });

    // Collapse/Expand link
    $('#export-tree').on('click', function () {
        event.preventDefault();
        if (locked) {
            return;
        }

        exportTree();
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

var centerTree = function (target, outer) {
    var out = $(outer);
    var tar = $(target);
    var x = out.width();
    var y = tar.outerWidth(true);
    var z = tar.index();
    out.scrollLeft(Math.max(0, (y * z) - (x - y) / 2));
};

var exportTree = function () {
    $html = $('html');
    dir = $html.attr('dir');
    $html.attr("dir", "ltr");
    $html.addClass('exporting');
    
    var $treeContainer = $('.jOrgChart');
    var $parent = $treeContainer.parent();
    var $saveButton = $('#save-tree');
    var $treeTable = $treeContainer.find('table');

    // Pre export
    var transform = $treeContainer.css('transform');
    var left = $parent.scrollLeft();
    var top = $parent.scrollTop();
    
    $treeContainer.css('transform', '');
    $('.genealogy-tree .node.dead').addClass('exporting');

    $parent.css('width', $treeTable.outerWidth());
    $parent.css('height', $treeTable.outerHeight());

    // Export
    html2canvas($treeTable, {
        width: $treeTable.outerWidth(),
        height: $treeTable.outerHeight(),
        background: '#eee',
        onrendered: function (canvas) {
//            document.body.appendChild(canvas);
            $saveButton.attr('href', canvas.toDataURL())[0].click();
        }
    });

    // Post export
    $treeContainer.css('transform', transform);
    $('.genealogy-tree .node.dead').removeClass('exporting');
    $parent.css('width', '');
    $parent.css('height', '');
    $parent.scrollLeft(left);
    $parent.scrollTop(top);
    $('html').removeClass('exporting');
    $html.attr('dir', dir);
};