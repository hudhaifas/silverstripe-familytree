jQuery(document).ready(function () {
    initTree();
});


var showPerson = function (url) {
    var param = '&ajax=1',
            ajaxUrl = (url.indexOf(param) === -1) ?
            url + '&ajax=1' :
            url,
            cleanUrl = url.replace(new RegExp(param + '$'), '');

    $('.ajax-loader').show();
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

                $('.ajax-loader').hide();
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
    var param = '&ajax=1',
            ajaxUrl = (url.indexOf(param) === -1) ?
            url + '&ajax=1' :
            url,
            cleanUrl = url.replace(new RegExp(param + '$'), '');

    console.log('ajaxUrl: ' + ajaxUrl);

    $.ajax(ajaxUrl)
            .done(function (response) {
                $("#panel-info").html(response);

////                    $('.main').html(response);
//                    $('html, body').animate({
//                        scrollTop: $('.main').offset().top
//                    });
//                    window.history.pushState(
//                            {url: cleanUrl},
//                            document.title,
//                            cleanUrl
//                            );
            })
            .fail(function (xhr) {
                alert('Error: ' + xhr.responseText);
            });
};

var initTree = function () {
    $("#family-relations").jOrgChart({
        chartElement: '#family-tree'
    });

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

//    reset();

    window.onpopstate = function (e) {
        console.log('e: ' + e);
        if (e.state.url) {
            console.log('URL: ' + e.state.url);
//            showPerson(e.state.url);
        } else {
            e.preventDefault();
        }
    };

};