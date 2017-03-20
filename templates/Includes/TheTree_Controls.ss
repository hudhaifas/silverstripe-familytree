<div id="controls-nav" class="navbar">
    <ul class="nav navbar-nav">        
        <li class="" data-intro="<%t Genealogist.TUTORIAL_FULLSCREEN 'Toggle Fullscreen mode' %>" data-position="left" >
            <a id="fullscreen-in-btn" href="#" title="<%t Genealogist.FULLSCREEN 'Fullscreen' %>">
                <i class="fa fa-expand" aria-hidden="true"></i>
            </a>

            <a id="fullscreen-out-btn" style="display: none;" href="#" title="<%t Genealogist.EXIT_FULLSCREEN 'Exit Fullscreen' %>">
                <i class="fa fa-compress" aria-hidden="true"></i>
            </a>
        </li>

        <li class="hide-on-timeline" data-intro="<%t Genealogist.TUTORIAL_COLLAPSE 'Collapse or expand all nodes in the tree.' %>" data-position="left" >
            <a id="collapse-btn" href="#" title="<%t Genealogist.COLLAPSE_ALL 'Collapse all nodes' %>">
                <i class="fa fa-leaf" aria-hidden="true"></i>
            </a>

            <a id="expand-btn" style="display: none;" href="#" title="<%t Genealogist.EXPAND_ALL 'Expand all nodes' %>">
                <i class="fa fa-pagelines" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="separator"></li>

        <li class="" data-intro="<%t Genealogist.TUTORIAL_ZOOM_IN 'Zoom In' %>" data-position="left" >
            <a id="zoom-in-btn" href="#" title="<%t Genealogist.ZOOM_IN 'Zoom In' %>">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="" data-intro="<%t Genealogist.TUTORIAL_ZOOM_ONE 'Reset the zoom' %>" data-position="left" >
            <a id="zoom-one-btn" href="#" title="<%t Genealogist.ZOOM_ONE 'Reset the zoom' %>">
                <i class="fa fa-search" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="" data-intro="<%t Genealogist.TUTORIAL_ZOOM_OUT 'Zoom Out' %>" data-position="left" >
            <a id="zoom-out-btn" href="#" title="<%t Genealogist.ZOOM_OUT 'Zoom Out' %>">
                <i class="fa fa-minus" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="separator"></li>

        <li class="hide-on-fullscreen" data-intro="<%t Genealogist.TUTORIAL_DOWNLOAD_TREE 'Save the family tree into an image' %>" data-position="left" >
            <a id="dwonload-btn" href="#" title="<%t Genealogist.DOWNLOAD_TREE 'Save Tree' %>">
                <i class="fa fa-download" aria-hidden="true"></i>
            </a>
            
            <a id="save-btn" class="hide"></a>
        </li>

    </ul>
</div>