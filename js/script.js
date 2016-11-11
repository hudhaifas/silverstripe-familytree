jQuery(document).ready(function () {
    initTree();
});


var showPerson = function (url) {
    var param = '&ajax=1';
    var ajaxUrl = (url.indexOf(param) === -1) ? url + param : url;
    var cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('#tree-loader').show();
    $('#family-tree').html('');

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
            })
            .fail(function (xhr) {
                alert('Error: ' + xhr.responseText);
            });
};

var initTree = function () {
    $("#family-relations").jOrgChart({
        chartElement: '#family-tree'
    });

    registerLinks();
    $('#family-tree').dragScroll({});


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
    $(".node a").click(function (event) {
        event.preventDefault();

        url = $(this).attr('data-url');
        updateInfo(url);
    });

    $("a.options-item").click(function (event) {
        event.preventDefault();

        url = ($(this).attr('href'));
        showPerson(url);
    });
};