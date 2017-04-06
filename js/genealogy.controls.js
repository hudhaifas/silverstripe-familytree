
var initAllControls = function () {
    initTreeNav();
    initTimelineNav();
    initControlsNav();

    initKinshipDropdown();
    initSearchTree();
};

var initTreeNav = function () {
    var $controls = $('<div class="chart-controls chart-controls-top-left"></div>');
    var $nav = $('#menu-nav');
    $controls.append($nav);
    $('#chart-container').append($controls);
};

var initTimelineNav = function () {
    var $controls = $('<div class="chart-controls chart-controls-bottom"></div>');
    var $nav = $('#timeline-nav');
    $controls.append($nav);
    $('#chart-container').append($controls);
};

var initControlsNav = function () {
    var $controls = $('<div class="chart-controls chart-controls-left vertical"></div>');
    var $nav = $('#controls-nav');
    $controls.append($nav);
    $('#chart-container').append($controls);

    initFullScreen();
};

/**
 * http://stackoverflow.com/questions/25089297/twitter-bootstrap-avoid-dropdown-menu-close-on-click-inside
 */
var initKinshipDropdown = function () {
    $('#kinsip-btn').on('click', function (event) {
        event.preventDefault();

        $(this).parent().toggleClass('open');
    });
};

var initSearchTree = function () {
    $('#search-input').unbind('propertychange change click keyup input paste');

    $('#search-input').on('propertychange change click keyup input paste', function (event) {
        event.preventDefault();

        $('.highlight').removeClass('highlight');

        akeyword = $(this).val();

        $nodes = $('.node a[title^="' + akeyword + '"]');

        $firstLink = $nodes.first();
        $firstNode = $firstLink.parent();
        console.log($nodes.length + ' results starts with: ' + akeyword);
        console.log('First result: ' + $firstLink.attr('title'));

        $firstNode.addClass("highlight");
        centerNode($firstNode);
    });
};

var initFullScreen = function () {
    var $container = $('#tree-container');
    var $fullscreenOnBtn = $('#fullscreen-in-btn');
    var $fullscreenOffBtn = $('#fullscreen-out-btn');

    toggleStrechContainer($container, $.fullscreen.isFullScreen());
    toggleFullscreenControls($fullscreenOnBtn, $fullscreenOffBtn);

    if ($fullscreenOnBtn && $fullscreenOffBtn) {
        $fullscreenOnBtn.click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            
            $container.fullscreen();
        });

        $fullscreenOffBtn.click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            
            $.fullscreen.exit();
        });

        // document's event
        $(document).bind('fscreenchange', function (e, state, elem) {
            toggleStrechContainer($container, $.fullscreen.isFullScreen());
            // if we currently in fullscreen mode
            toggleFullscreenControls($fullscreenOnBtn, $fullscreenOffBtn);
        });
    }
};

var toggleFullscreenControls = function ($fullscreenOnBtn, $fullscreenOffBtn) {
    if ($.fullscreen.isFullScreen()) {
        $('.hide-on-fullscreen').hide();
        $fullscreenOnBtn.hide();
        $fullscreenOffBtn.show();

    } else {
        $('.hide-on-fullscreen').show();
        $fullscreenOnBtn.show();
        $fullscreenOffBtn.hide();
    }
};

var toggleStrechContainer = function ($container, isStrech) {
    if (isStrech) {
        $container.addClass('strech');
//        $container.css('width', '100%');
//        $container.css('max-width', '100%');
//        $container.css('height', '100%');
//        $container.css('max-height', '100%');

    } else {
        $container.removeClass('strech');
//        $container.css('width', '');
//        $container.css('max-width', '');
//        $container.css('height', '');
//        $container.css('max-height', '');
    }
};

var filterRoots = function () {
    var input, filter, ul, li, a, i;
    input = document.getElementById("filter-input");
    filter = input.value.toUpperCase();
    ul = document.getElementById("filter-list");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";

        }
    }
};

var lockAll = function () {
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', true);
    locked = true;
};

var unlockAll = function () {
    locked = false;
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', false);
};

var bindAll = function () {
    unbindAll();

    // Open modal in AJAX callback
    $('.ajax-modal').click(function (event) {
        event.preventDefault();
        loadModal(this.href);
    });

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
        $('.highlight').removeClass('highlight');
    });

    $('.ajax-modal').click(function () {
        if (locked) {
            return;
        }

        hideInfoCard();
        $('.highlight').removeClass('highlight');
    });

    window.onpopstate = function (e) {
        console.log('e: ' + e);
        if (e.state && e.state.url) {
            console.log('onpopstate: ' + e.state.url);
            showPerson(e.state.url);
        } else {
            e.preventDefault();
        }
    };

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

    $('#ttt').dropdown();

};

var unbindAll = function () {
    $("#toggle-fullscreen, #toggle-nodes, #export-tree, input[type=checkbox]").unbind("click");
};
