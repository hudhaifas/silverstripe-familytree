jQuery(document).ready(function () {
    $("#family-relations").jOrgChart({
        chartElement: '#family-tree',
    });

    $(".node a").click(function (event) {
        event.preventDefault();
        url = $(this).attr('data-url');
        updateInfo(url);
    });
});

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