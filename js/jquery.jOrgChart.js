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
        var $container = $(opts.chartElement);
        $container.addClass('chart-container');
        $contentPane = $('<div class="chart-content-pane"></div>');
        $container.append($contentPane);

        // build the tree
        $this = $(this);
        var $chartPane = $("<div class='" + opts.chartClass + " " + opts.direction + "'/>");
        if ($this.is("ul")) {
            buildNode($this.find("li:first"), $chartPane, 0, opts);
        } else if ($this.is("li")) {
            buildNode($this, $chartPane, 0, opts);
        }

        $this.remove();
        $contentPane.append($chartPane);

        $controls = createControls($container, $contentPane, $chartPane, opts);
        $container.append($controls);

        $extraPane = $('<div id="chart-extra" class="chart-extra"></div>');
        $container.append($extraPane);

        $container.append('<div id="info-card" class="info-card"></div>');
        
        if (opts.zoom && opts.zoomScroller) {
            setupZoom($container, $chartPane, opts);
        }

        $chartTable = $chartPane.find('table');
//        $chartPane.css('width', $chartTable.outerWidth());
//        $chartPane.css('height', $chartTable.outerHeight());

//        centerTree($chartPane.find('table'), $contentPane);
//        centerTree($chartTable, $contentPane);
    };

    // Default options
    $.fn.jOrgChart.defaults = {
        chartElement: 'body',
        multipleRoot: false, // Support multiple roots tree
        depth: -1, // all
        chartClass: "chart-pane",
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
    function buildNode($node, $container, level, opts) {
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
                .append($nodeContent)
//                .append('<div class="info-card"></div>')
                ;

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

        /* Support multiple roots tree */
        if (level > 0 || !opts.multipleRoot) {
            $tbody.append($nodeRow);
        }

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

                /* Support multiple roots tree */
                if (level > 0 || !opts.multipleRoot) {
                    $tbody.append($downLineRow);
                }

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

                /* Support multiple roots tree */
                if (level > 0 || !opts.multipleRoot) {
                    $tbody.append($linesRow);
                }

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

        if ($node.attr('data-birth') != undefined) {
            item = $node.attr('data-birth');
            $nodeDiv.attr('data-birth', item);
        }
        if ($node.attr('data-death') != undefined) {
            item = $node.attr('data-death');
            $nodeDiv.attr('data-death', item);
        }

        $table.append($tbody);
        $container.append($table);

        /* Prevent trees collapsing if a link inside a node is clicked */
        $nodeDiv.children('a').click(function (e) {
//            console.log(e);
            e.stopPropagation();
        });
    }

    function createControls($container, $contentPane, $chartPane, opts) {
        $controls = $('<div class="chart-controls"></div>');

        if (opts.fullscreen) {
            var $fullscreenBtn = createButton('fullscreen', function (event) {
                event.preventDefault();
                $container.fullscreen();
            });
            var $exitBtn = createButton('fullscreen-exit', function (event) {
                event.preventDefault();
                $.fullscreen.exit();
            });
            $exitBtn.css('display', 'none');

            $fullscreenBtn.appendTo($controls);
            $exitBtn.appendTo($controls);

            // document's event
            $(document).bind('fscreenchange', function (e, state, elem) {
                strechScreen($container, $.fullscreen.isFullScreen());
                // if we currently in fullscreen mode
                if ($.fullscreen.isFullScreen()) {
                    $('.no-fullscreen').hide();
                    $fullscreenBtn.hide();
                    $exitBtn.show();
                } else {
                    $('.no-fullscreen').show();
                    $fullscreenBtn.show();
                    $exitBtn.hide();
                }
            });
        }

        var $collapseBtn = createButton('collapse-all', function (event) {
            event.preventDefault();
            toggleAllNodes($container);
            $container.find('.collapse-all, .expand-all').toggleClass('collapse-all expand-all');
        });
        $collapseBtn.appendTo($controls);

        if (opts.dragScroller) {
            $contentPane.dragScroll({});
        }

        if (opts.zoom) {
            var $zoomInBtn = createButton('zoom-in', function (event) {
                event.preventDefault();
                var newScale = 1 + opts.stepZoom;
                changeZoom($chartPane, newScale, opts);
            });
            var $zoomOutBtn = createButton('zoom-out', function (event) {
                event.preventDefault();
                var newScale = 1 + -(opts.stepZoom);
                changeZoom($chartPane, newScale, opts);
            });

            $zoomInBtn.appendTo($controls);
            $zoomOutBtn.appendTo($controls);
        }

        if (opts.exportImage) {
            var $saveBtn = $('<a href="#" id="save-tree" class="hidden" download="' + opts.exportFile + '"></a>');
            var $exportBtn = createButton('export no-fullscreen hidden-phone hidden-tablet', function () {
                event.preventDefault();

                exportTree($container, $contentPane, $chartPane, $saveBtn);
            });

            $exportBtn.appendTo($controls);
            $saveBtn.appendTo($controls);
        }

        return $controls;
    }

    function createButton(icon, onclick) {
        $btn = $('<div class="chart-control ' + icon + '"></div> ');
        $btn.on('click', onclick);

        return $btn;
    }

    function toggleAllNodes($chartPane) {
        $nodeDiv = $chartPane.find('div.node');
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

    function strechScreen($container, strech) {
        if (strech) {
            $container.css('width', '100%');
            $container.css('max-width', '100%');
            $container.css('height', '100%');
            $container.css('max-height', '100%');

        } else {
            $container.css('width', '');
            $container.css('max-width', '');
            $container.css('height', '');
            $container.css('max-height', '');
        }
    }

    function exportTree($container, $contentPane, $chartPane, $saveBtn) {
        $.fullscreen.exit();

        $html = $('html');
        dir = $html.attr('dir');
        $html.attr("dir", "ltr");
        $html.addClass('exporting');

        var $chartTable = $chartPane.find('table');

        // Pre export
        // Set the HTML page to defaults:
        // - direction: ltr
        // - float: left
        // - scale:1
        // - width:
        var transform = $chartPane.css('transform');
        var left = $contentPane.scrollLeft();
        var top = $contentPane.scrollTop();

        $chartPane.css('transform', '');
        $('.genealogy-tree .node.dead').addClass('exporting');

        $container.css('width', $chartTable.outerWidth());
        $container.css('height', $chartTable.outerHeight());

        // Export
        html2canvas($chartTable, {
            width: $chartTable.outerWidth(),
            height: $chartTable.outerHeight(),
            background: '#eee',
            onrendered: function (canvas) {
//            document.body.appendChild(canvas);
                $saveBtn.attr('href', canvas.toDataURL())[0].click();
            }
        });

        // Post export
        $chartPane.css('transform', transform);
        $('.genealogy-tree .node.dead').removeClass('exporting');
        $container.css('width', '');
        $container.css('height', '');
        $contentPane.scrollLeft(left);
        $contentPane.scrollTop(top);
        $('html').removeClass('exporting');
        $html.attr('dir', dir);
    }

    function setupZoom($container, $chartPane, opts) {
        $container
                .on('wheel', function (event) {
                    event.preventDefault();
                    var newScale = 1 + (event.originalEvent.deltaY > 0 ? -(opts.stepZoom) : opts.stepZoom);
                    changeZoom($chartPane, newScale, opts);
                });

        $container
                .on('touchstart', function (e) {
                    if (e.touches && e.touches.length === 2) {
                        $chartPane.data('pinching', true);
                        var dist = getPinchDist(e);
                        $chartPane.data('pinchDistStart', dist);
                    }
                });

        $(document)
                .on('touchmove', function (e) {
                    if ($chartPane.data('pinching')) {
                        var dist = getPinchDist(e);
                        $chartPane.data('pinchDistEnd', dist);
                    }
                })
                .on('touchend', function (e) {
                    if ($chartPane.data('pinching')) {
                        $chartPane.data('pinching', false);
                        var diff = $chartPane.data('pinchDistEnd') - $chartPane.data('pinchDistStart');
                        if (diff > 0) {
                            changeZoom($chartPane, 1.2);
                        } else if (diff < 0) {
                            changeZoom($chartPane, 0.8);
                        }
                    }
                });
    }

    function changeZoom($chartPane, newScale, opts) {
        $parent = $chartPane.parent();
        pW = $parent.width();
        pH = $parent.height();
        cW = $chartPane.width();
        cH = $chartPane.height();

//        console.log('Container: [' + cW + ',' + cH + ']');
//        console.log('Parent: [' + pW + ',' + pH + ']');

        currentScale = getCurrentScale($chartPane);
        if ((newScale > 1 && currentScale > opts.maxZoom) || (newScale < 1 && currentScale < opts.minZoom)) {
            return;
        }

        var lastTf = $chartPane.css('transform');

        if (lastTf === 'none') {
            $chartPane.css('transform', 'scale(' + newScale + ',' + newScale + ')');

        } else {
            if (lastTf.indexOf('3d') === -1) {
                $chartPane.css('transform', lastTf + ' scale(' + newScale + ',' + newScale + ')');
            } else {
                $chartPane.css('transform', lastTf + ' scale3d(' + newScale + ',' + newScale + ', 1)');
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

    var centerTree = function (table, container) {
        var out = $(container);
        var tar = $(table);
        var x = out.width();
        var y = tar.outerWidth(true);
        var z = tar.index();
        out.scrollLeft(Math.max(0, (y * z) - (x - y) / 2));
    };

})(jQuery);
