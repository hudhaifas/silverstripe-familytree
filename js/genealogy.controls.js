function filterRoots() {
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
}

var initTreeNav = function () {
    var $controls = $('<div class="chart-controls-hor"></div>');
    var $nav = $('#menu-nav');
    $controls.append($nav);
    $('#k1').append($controls);
};

var initTimelineNav = function () {
    var $controls = $('<div class="chart-controls-bottom"></div>');
    var $nav = $('#timeline-nav');
    $controls.append($nav);
    $('#k1').append($controls);
};

var initControlsNav = function () {
    var $controls = $('<div class="chart-controls-right"></div>');
    var $nav = $('#controls-nav');
    $controls.append($nav);
    $('#k1').append($controls);
};

/**
 * http://stackoverflow.com/questions/25089297/twitter-bootstrap-avoid-dropdown-menu-close-on-click-inside
 */
function initKinshipDropdown() {
    $('#kinsip-btn').on('click', function (event) {
        event.preventDefault();

        $(this).parent().toggleClass('open');
    });
}

function initSearchTree() {
    $('#search-input').on('propertychange change click keyup input paste', function (event) {
        event.preventDefault();

        $('.highlight').removeClass('highlight');

        akeyword = $(this).val();

        $nodes = $('.node a[title^="' + akeyword + '"]');

        $firstLink = $nodes.first();
        $firstNode = $firstLink.parent();
//        console.log($nodes.length + ' results starts with: ' + akeyword);
//        console.log('First result: ' + $firstLink.attr('title'));

        $firstNode.addClass("highlight");
        centerNode($firstNode);
    });
}

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

    $('#search-input').unbind('propertychange change click keyup input paste');
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
        $('.highlight').removeClass('highlight');
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
