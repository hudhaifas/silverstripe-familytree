jQuery(document).ready(function () {
    initTree();

    // Scroll to the tree div
    $('html, body').animate({
        scrollTop: $('.tree-container').offset().top
    }, 'slow');
});


var showPerson = function (url) {
    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;
    var cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('#tree-loader').show();
    $('#genealogy-tree').html('');

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

    $.ajax(ajaxUrl)
            .done(function (response) {
                $("#panel-info").html(response);
                registerLinks();
                $('#info-loader').hide();
                $('#collapse2-btn').trigger('click');
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

var registerLinks = function () {
    $("a.info-item").click(function (event) {
        event.preventDefault();

        url = $(this).attr('data-url');
        updateInfo(url);
    });

    $("a.options-item").click(function (event) {
        event.preventDefault();

        url = ($(this).attr('href'));
        showPerson(url);
    });

    // Full-screen link
    $('#toggle-fullscreen').on('click', function () {
        event.preventDefault();
        $('.tree-container').toggleFullScreen();

    });
};