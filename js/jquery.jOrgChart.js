/**
 * jQuery org-chart/tree plugin.
 *
 * Author: Wes Nolte
 * http://twitter.com/wesnolte
 *
 * Based on the work of Mark Lee
 * http://www.capricasoftware.co.uk 
 *
 * Copyright (c) 2011 Wesley Nolte
 * Dual licensed under the MIT and GPL licenses.
 *
 */
(function ($) {

    $.fn.jOrgChart = function (options) {
        var opts = $.extend({}, $.fn.jOrgChart.defaults, options);
        var $appendTo = $(opts.chartElement);

        // build the tree
        $this = $(this);
        var $container = $("<div class='" + opts.chartClass + " " + opts.direction + "'/>");
        if ($this.is("ul")) {
            buildNode($this.find("li:first"), $container, 0, opts);
        } else if ($this.is("li")) {
            buildNode($this, $container, 0, opts);
        }

        $this.remove();
        $appendTo.append($container);
        appendControls($appendTo, $container, opts);

        if (opts.zoom && opts.zoomScroller) {
            setupZoom($appendTo, $container, opts);
        }
    };

    // Option defaults
    $.fn.jOrgChart.defaults = {
        chartElement: 'body',
        depth: -1,
        chartClass: "jOrgChart",
        // Direction options
        direction: "t2b",
        // Fullscree options
        fullscreen: true,
        // Drag scroller options
        dragScroller: true,
        // Export options
        exportImage: true,
        exportFile: 'family.png',
        // Zoom options
        zoom: true,
        zoomScroller: false,
        minZoom: 0.4,
        maxZoom: 1.2,
        stepZoom: 0.2
    };

    var nodeCount = 0;
    // Method that recursively builds the tree
    function buildNode($node, $appendTo, level, opts) {
        var $table = $("<table cellpadding='0' cellspacing='0' border='0'/>");
        var $tbody = $("<tbody/>");

        // Construct the node container(s)
        var $nodeRow = $("<tr/>").addClass("node-cells");
        var $nodeCell = $("<td/>").addClass("node-cell").attr("colspan", 2);
        var $childNodes = $node.children("ul:first").children("li");
        var $nodeDiv;

        if ($childNodes.length > 1) {
            $nodeCell.attr("colspan", $childNodes.length * 2);
        }
        // Draw the node
        // Get the contents - any markup except li and ul allowed
        var $nodeContent = $node.clone()
                .children("ul,li")
                .remove()
                .end()
                .html();

        //Increaments the node count which is used to link the source list and the org chart
        nodeCount++;
        $node.data("tree-node", nodeCount);
        $nodeDiv = $("<div>").addClass("node")
                .data("tree-node", nodeCount)
                .append($nodeContent);

        // Expand and contract nodes
        if ($childNodes.length > 0) {
            $nodeDiv.click(function () {
                var $this = $(this);
                var $tr = $this.closest("tr");

                if ($tr.hasClass('contracted')) {
                    $this.css('cursor', 'zoom-out');
                    $tr.removeClass('contracted').addClass('expanded');
                    $tr.nextAll("tr").css('visibility', '');
                    $tr.nextAll("tr").css('display', '');

                    // Update the <li> appropriately so that if the tree redraws collapsed/non-collapsed nodes
                    // maintain their appearance
                    $node.removeClass('collapsed');
                } else {
                    $this.css('cursor', 'zoom-in');
                    $tr.removeClass('expanded').addClass('contracted');
                    $tr.nextAll("tr").css('visibility', 'hidden');
                    $tr.nextAll("tr").css('display', 'none');

                    $node.addClass('collapsed');
                }
            });
        }

        $nodeCell.append($nodeDiv);
        $nodeRow.append($nodeCell);
        $tbody.append($nodeRow);

        if ($childNodes.length > 0) {
            // if it can be expanded then change the cursor
            $nodeDiv.css('cursor', 'zoom-out');

            // recurse until leaves found (-1) or to the level specified
            if (opts.depth == -1 || (level + 1 < opts.depth)) {
                var $downLineRow = $("<tr/>");
                var $downLineCell = $("<td/>").attr("colspan", $childNodes.length * 2);
                $downLineRow.append($downLineCell);

                // draw the connecting line from the parent node to the horizontal line 
                $downLine = $("<div></div>").addClass("line down");
                $downLineCell.append($downLine);
                $tbody.append($downLineRow);

                // Draw the horizontal lines
                var $linesRow = $("<tr/>");
                $childNodes.each(function () {
                    var $left = $("<td>&nbsp;</td>").addClass("line left top");
                    var $right = $("<td>&nbsp;</td>").addClass("line right top");
                    $linesRow.append($left).append($right);
                });

                // horizontal line shouldn't extend beyond the first and last child branches
                $linesRow.find("td:first")
                        .removeClass("top")
                        .end()
                        .find("td:last")
                        .removeClass("top");

                $tbody.append($linesRow);
                var $childNodesRow = $("<tr/>");
                $childNodes.each(function () {
                    var $td = $("<td class='node-container'/>");
                    $td.attr("colspan", 2);
                    // recurse through children lists and items
                    buildNode($(this), $td, level + 1, opts);
                    $childNodesRow.append($td);
                });

            }
            $tbody.append($childNodesRow);
        }

        // any classes on the LI element get copied to the relevant node in the tree
        // apart from the special 'collapsed' class, which collapses the sub-tree at this point
        if ($node.attr('class') != undefined) {
            var classList = $node.attr('class').split(/\s+/);
            $.each(classList, function (index, item) {
                if (item == 'collapsed') {
                    console.log($node);
                    $nodeRow.nextAll('tr').css('visibility', 'hidden');
                    $nodeRow.removeClass('expanded');
                    $nodeRow.addClass('contracted');
                    $nodeDiv.css('cursor', 'zoom-in');
                } else {
                    $nodeDiv.addClass(item);
                }
            });
        }

        $table.append($tbody);
        $appendTo.append($table);

        /* Prevent trees collapsing if a link inside a node is clicked */
        $nodeDiv.children('a').click(function (e) {
            console.log(e);
            e.stopPropagation();
        });
    }

    function appendControls($appendTo, $container, opts) {
        $controls = $('<div class="chart-controls"></div>');

        if (opts.fullscreen) {
            var $fullscreen = createButton('window-maximize', function () {

                event.preventDefault();
                $appendTo.toggleFullScreen();
            });
            $fullscreen.appendTo($controls);

            $(document).bind("fullscreenchange", function () {
                strechScreen($appendTo, $appendTo.fullScreen());
                $appendTo.find('.fa-window-maximize, .fa-window-restore').toggleClass('fa-window-maximize fa-window-restore');
            });
        }

        var $collapse = createButton('compress', function () {
            event.preventDefault();
            if (toggleAllNodes($appendTo)) {
                $(this).find('.fa').removeClass('fa-expand').addClass('fa-compress');
            } else {
                $(this).find('.fa').removeClass('fa-compress').addClass('fa-expand');
            }
        });
        $collapse.appendTo($controls);

        if (opts.dragScroller) {
            $appendTo.dragScroll({});
        }

        if (opts.zoom) {
            var $zoomIn = createButton('plus', function () {
                event.preventDefault();
                var newScale = 1 + opts.stepZoom;
                changeZoom($container, newScale, opts);
            });
            var $zoomOut = createButton('minus', function () {
                event.preventDefault();
                var newScale = 1 + -(opts.stepZoom);
                changeZoom($container, newScale, opts);
            });

            $zoomIn.appendTo($controls);
            $zoomOut.appendTo($controls);
        }

        if (opts.exportImage) {
            var $export = createButton('picture-o', function () {
                event.preventDefault();

                exportTree($appendTo);
            });
            var $save = $('<a href="#" id="save-tree" class="hidden" download="' + opts.exportFile + '"></a>');

            $export.appendTo($controls);
            $save.appendTo($controls);
        }

        $controls.appendTo($appendTo);
    }

    function createButton(icon, onclick) {
        $btn = $('<a class="chart-control"><i class="fa fa-' + icon + '"></i></a> ');
        $btn.on('click', onclick);

        return $btn;
    }

    function toggleAllNodes($container) {
        $nodeDiv = $container.find('div.node');
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
    }

    function strechScreen($appendTo, strech) {
        if (strech) {
            $appendTo.css('width', '100%');
            $appendTo.css('max-width', '100%');
            $appendTo.css('height', '100%');
            $appendTo.css('max-height', '100%');
        } else {
            $appendTo.css('width', '');
            $appendTo.css('max-width', '');
            $appendTo.css('height', '');
            $appendTo.css('max-height', '');
        }
    }

    function exportTree($parent) {
        $html = $('html');
        dir = $html.attr('dir');
        $html.attr("dir", "ltr");
        $html.addClass('exporting');

        var $treeContainer = $parent.find('.jOrgChart');
        var $saveButton = $('#save-tree');
        var $treeTable = $treeContainer.find('table');

        // Pre export
        // Set the HTML page to:
        // - direction: ltr 
        // - float: left
        // - scale:1
        // - width: 
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
    }

    function setupZoom($appendTo, $container, opts) {
        $appendTo
                .on('wheel', function (event) {
                    event.preventDefault();
                    var newScale = 1 + (event.originalEvent.deltaY > 0 ? -(opts.stepZoom) : opts.stepZoom);
                    changeZoom($container, newScale, opts);
                });

        $appendTo
                .on('touchstart', function (e) {
                    if (e.touches && e.touches.length === 2) {
                        $container.data('pinching', true);
                        var dist = getPinchDist(e);
                        $container.data('pinchDistStart', dist);
                    }
                });

        $(document)
                .on('touchmove', function (e) {
                    if ($container.data('pinching')) {
                        var dist = getPinchDist(e);
                        $container.data('pinchDistEnd', dist);
                    }
                })
                .on('touchend', function (e) {
                    if ($container.data('pinching')) {
                        $container.data('pinching', false);
                        var diff = $container.data('pinchDistEnd') - $container.data('pinchDistStart');
                        if (diff > 0) {
                            changeZoom($container, 1.2);
                        } else if (diff < 0) {
                            changeZoom($container, 0.8);
                        }
                    }
                });
    }

    function changeZoom($container, newScale, opts) {
        $parent = $container.parent();
        pW = $parent.width();
        pH = $parent.height();
        cW = $container.width();
        cH = $container.height();

        console.log('Container: [' + cW + ',' + cH + ']');
        console.log('Parent: [' + pW + ',' + pH + ']');



        currentScale = getCurrentScale($container);
        if ((newScale > 1 && currentScale > opts.maxZoom) || (newScale < 1 && currentScale < opts.minZoom)) {
            return;
        }

        var lastTf = $container.css('transform');

        if (lastTf === 'none') {
            $container.css('transform', 'scale(' + newScale + ',' + newScale + ')');

        } else {
            if (lastTf.indexOf('3d') === -1) {
                $container.css('transform', lastTf + ' scale(' + newScale + ',' + newScale + ')');
            } else {
                $container.css('transform', lastTf + ' scale3d(' + newScale + ',' + newScale + ', 1)');
            }
        }
    }

    function parseMatrix(_str) {
        return _str.replace(/^matrix(3d)?\((.*)\)$/, '$2').split(/, /);
    }

    function getCurrentScale($element) {
        var matrix = parseMatrix(getMatrix($element));
        var scale = 1;

        if (matrix[0] !== 'none') {
            var a = matrix[0];
            var b = matrix[1];
            var d = 10;
            scale = Math.round(Math.sqrt(a * a + b * b) * d) / d;
        }

        return scale;
    }

    function getMatrix($element) {
        var matrix = $element.css("-webkit-transform") ||
                $element.css("-moz-transform") ||
                $element.css("-ms-transform") ||
                $element.css("-o-transform") ||
                $element.css("transform");
        return matrix;
    }

})(jQuery);
