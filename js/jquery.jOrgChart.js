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

        $controls = setupControls($container, $contentPane, $chartPane, opts);
        $container.append($controls);

        $extraPane = $('<div id="chart-extra" class="chart-extra"></div>');
        $container.append($extraPane);

        $container.append('<div id="info-card" class="info-card"></div>');

        if (opts.zoom && opts.zoomScroller) {
//            setupZoom($container, $contentPane, $chartPane, opts);
        }

        $chartTable = $chartPane.find('table');
    };

    // Default options
    $.fn.jOrgChart.defaults = {
        chartElement: 'body',
        // Support multiple roots tree
        multipleRoot: false,
        // Tree depth
        depth: -1, // all
        chartClass: "chart-pane",
        // Direction options
        direction: "t2b",
        // Fullscree options
        fullscreenOnBtn: null,
        fullscreenOffBtn: null,
        // Drag scroller options
        dragScroller: true,
        // Collapse options
        collapsible: true,
        collapseBtn: null,
        expandBtn: null,
        // Export options
        exportBtn: null,
        exportFile: 'family.png',
        // Zoom options
        zoom: true,
        zoomInBtn: null,
        zoomOneBtn: null,
        zoomOutBtn: null,
        zoomScroller: false,
        minZoom: 0.4,
        maxZoom: 1.6,
        stepZoom: 0.1
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
                .append($nodeContent);

        // Expand and contract nodes
        if ($childNodes.length > 0) {
            $collapseBtn = $('<div class="collaplse-btn no-collapse hide-on-timeline"></div>');
            $nodeDiv.append($collapseBtn);

            $collapseBtn.click(function () {
                var $this = $(this);
                var $tr = $this.parent().closest("tr");

                if ($tr.hasClass('contracted')) {
                    $this.removeClass('expand');
                    $tr.removeClass('contracted').addClass('expanded');
                    $tr.nextAll("tr").css('visibility', '');
                    $tr.nextAll("tr").css('display', '');

                    // Update the <li> appropriately so that if the tree redraws collapsed/non-collapsed nodes
                    // maintain their appearance
                    $node.removeClass('collapsed');
                } else {
                    $this.addClass('expand');
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
                    $nodeRow.nextAll('tr').css('visibility', 'hidden');
                    $nodeRow.removeClass('expanded');
                    $nodeRow.addClass('contracted');
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

    function setupControls($container, $contentPane, $chartPane, opts) {
        $controls = $('<div class="hidden"></div>');

//        if (opts.fullscreenOnBtn && opts.fullscreenOffBtn) {
//            opts.fullscreenOnBtn.click(function (event) {
//                event.stopPropagation();
//                $container.fullscreen();
//            });
//            opts.fullscreenOffBtn.click(function (event) {
//                event.stopPropagation();
//                $.fullscreen.exit();
//            });
//
//            // document's event
//            $(document).bind('fscreenchange', function (e, state, elem) {
//                toggleStrechContainer($container, $.fullscreen.isFullScreen());
//                // if we currently in fullscreen mode
//                if ($.fullscreen.isFullScreen()) {
//                    $('.hide-on-fullscreen').hide();
//                    opts.fullscreenOnBtn.hide();
//                    opts.fullscreenOffBtn.show();
//
//                } else {
//                    $('.hide-on-fullscreen').show();
//                    opts.fullscreenOnBtn.show();
//                    opts.fullscreenOffBtn.hide();
//                }
//            });
//        }

        if (!opts.collapsible) {
            $('.no-collapse').hide();
        } else {
            $('.no-collapse').show();
        }

        if (opts.collapseBtn && opts.expandBtn) {
            opts.collapseBtn.click(function (event) {
                event.preventDefault();

                toggleAllNodes($container, opts);
            });
            opts.expandBtn.click(function (event) {
                event.preventDefault();
                toggleAllNodes($container, opts);
            });
        }

        if (opts.dragScroller) {
            $contentPane.dragScroll({});
        }

        if (opts.zoom) {
            if (opts.zoomInBtn) {
                opts.zoomInBtn.click(function (event) {
                    event.preventDefault();
                    zoom($contentPane, $chartPane, opts.stepZoom, opts);
                });
            }

            if (opts.zoomOneBtn) {
                opts.zoomOneBtn.click(function (event) {
                    event.preventDefault();
                    zoom($contentPane, $chartPane, 0, opts);
                });
            }

            if (opts.zoomOutBtn) {
                opts.zoomOutBtn.click(function (event) {
                    event.preventDefault();
                    zoom($contentPane, $chartPane, -opts.stepZoom, opts);
                });
            }
        }

        if (opts.exportBtn) {
            if (!opts.saveBtn) {
                opts.saveBtn = $('<a href="#" id="save-tree" class="hidden" download="' + opts.exportFile + '"></a>');
                opts.saveBtn.appendTo($controls);
            }

            opts.exportBtn.click(function (event) {
                event.preventDefault();

                exportTree($container, $contentPane, $chartPane, opts.saveBtn);
            });
        }

        return $controls;
    }

    function toggleAllNodes($chartPane, opts) {
        $nodeDiv = $chartPane.find('div.node');
        $collapseBtn = $nodeDiv.find('.collaplse-btn');

        var $tr = $nodeDiv.closest("tr");

        if ($tr.hasClass('contracted')) {
            $collapseBtn.removeClass('expand');

            $tr.removeClass('contracted').addClass('expanded');
            $tr.nextAll("tr").css('visibility', '');
            $tr.nextAll("tr").css('display', '');

            opts.collapseBtn.show();
            opts.expandBtn.hide();

            return true;
        } else {
            $collapseBtn.addClass('expand');

            $tr.removeClass('expanded').addClass('contracted');
            $tr.nextAll("tr").css('visibility', 'hidden');
            $tr.nextAll("tr").css('display', 'none');

            opts.collapseBtn.hide();
            opts.expandBtn.show();
            return false;
        }
    }

    function toggleStrechContainer($container, isStrech) {
        if (isStrech) {
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

        var $html = $('html');
        var dir = $html.attr('dir');
        $html.attr("dir", "ltr");
        $html.addClass('exporting');

        var $chartTable = $chartPane.find('table');
        var $allNodes = $chartPane.find('.node.dead');

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

        $allNodes.addClass('exporting');

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
        $container.css('width', '');
        $container.css('height', '');
        $contentPane.scrollLeft(left);
        $contentPane.scrollTop(top);

        $allNodes.removeClass('exporting');
        $chartPane.css('transform', transform);

        $html.removeClass('exporting');
        $html.attr('dir', dir);
    }

    function setupZoom($container, $contentPane, $chartPane, opts) {
        $container
                .on('wheel', function (event) {
                    event.preventDefault();
                    var newScale = event.originalEvent.deltaY > 0 ? -opts.stepZoom : opts.stepZoom;
                    zoom($contentPane, $chartPane, newScale, opts);
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
                            zoom($contentPane, $chartPane, opts.stepZoom, opts);
                        } else if (diff < 0) {
                            zoom($contentPane, $chartPane, -opts.stepZoom, opts);
                        }
                    }
                });
    }

    var curScale = 1;
    function zoom($contentPane, $chartPane, change, opts) {
        // Determine current scroll positions
        var curScrollTop = $contentPane.prop('scrollTop');
        var curScrollLeft = $contentPane.prop('scrollLeft');
        var newScroll = {};
        var newScale;

        if (change === 0) {
            newScale = 1;
        } else {
            newScale = curScale + change;
        }

//        if ((newScale) > opts.maxZoom || (newScale) < opts.minZoom) {
//            return;
//        }

        var ratio = newScale / curScale;
        newScroll.scrollTop = curScrollTop * ratio;
        newScroll.scrollLeft = curScrollLeft * ratio;

        curScale = newScale;
        $chartPane.css('transform', 'scale(' + curScale + ',' + curScale + ')');

        if (opts.zoomInBtn) {
            opts.zoomInBtn.attr('disabled', curScale >= opts.maxZoom);
        }

        if (opts.zoomOneBtn) {
            opts.zoomOneBtn.attr('disabled', curScale === 1);
        }

        if (opts.zoomOutBtn) {
            opts.zoomOutBtn.attr('disabled', curScale <= opts.minZoom);
        }

        $contentPane.scrollTop(newScroll.scrollTop);
        $contentPane.scrollLeft(newScroll.scrollLeft);
//        $contentPane.animate(newScroll, {
//            duration: 400,
//            easing: 'linear'
//        });
    }

})(jQuery);