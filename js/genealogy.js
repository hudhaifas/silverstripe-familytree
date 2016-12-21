var locked = false;

jQuery(document).ready(function () {
    initTree();

    fillForms();

    // Scroll to the tree div
    $('html, body').animate({
        scrollTop: $('.tree-container').offset().top
    }, 'slow');
});

var showPerson = function (url) {
    console.log('url: ' + url);

    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;
    var cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('#tree-loader').show();
    $('#genealogy-tree').html('');

    lockLinks();

    console.log('ajaxUrl: ' + ajaxUrl);
    $.ajax(ajaxUrl)
            .done(function (response) {
                $('#tree-holder').html(response);
                initTree();

                window.history.pushState(
                        {url: cleanUrl},
                        document.title,
                        cleanUrl
                        );

                $('#tree-loader').hide();

                releaseLinks();
            })
            .fail(function (xhr) {
                alert('Error: ' + xhr.responseText);
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

    $.ajax(ajaxUrl)
            .done(function (response) {
                $("#panel-info").html(response);
                registerLinks();
                $('#info-loader').hide();
                $('#collapse-info-btn').trigger('click');

                releaseLinks();
            })
            .fail(function (xhr) {
                alert('Error: ' + xhr.responseText);
            });
};

var initTree = function () {
    $("#genealogy-relations").jOrgChart({
        chartElement: '#genealogy-tree'
    });

    registerLinks();
    $('#genealogy-tree').dragScroll({});

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

var fillForms = function () {
    url = $(location).attr('href');
    var uri = URI(url);
    console.log(uri.toString());

    var params = uri.search(true);
    console.log(params);

    $('input.options-check').each(function () {
        id = $(this).attr('id');
        status = params[id];
        $(this).prop('checked', status == 1 ? true : false);
    });

};

var registerLinks = function () {
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

    // Full-screen link
    $('#toggle-fullscreen').on('click', function () {
        event.preventDefault();
        if (locked) {
            return;
        }

        $('.tree-container').toggleFullScreen();

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
        var uri = URI(url);

        var params = {};
        $('input[type=checkbox].options-check').each(function () {
            id = $(this).attr('id');
            value = this.checked ? 1 : 0;
            params[id] = value;
        });
        uri.setSearch(params);

        showPerson(uri.toString());
    });
};

var lockLinks = function () {
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', true);
    locked = true;
};

var releaseLinks = function () {
    locked = false;
    $('a.info-item, a.options-item, #toggle-fullscreen, input.options-check').attr('disabled', false);
};