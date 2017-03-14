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

        $firstNode = $nodes.first().parent();
        console.log($nodes.length + ' results starts with: ' + akeyword);
        console.log('First result: ' + $firstNode.attr('title'));

//        $firstNode.effect("highlight", {}, 3000);
        $firstNode.addClass("highlight");

        
        centerNode($firstNode);
    });
}