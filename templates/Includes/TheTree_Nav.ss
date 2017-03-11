<div id="menu-nav" class="navbar">
    <ul class="nav navbar-nav">        
        <li class="no-timeline">
            <a id="timeline-btn" href="#" title="<%t Genealogist.TIMELINE 'Timeline' %>">
                <i class="fa fa-history" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="<%t Genealogist.FILTERS 'Filters' %>">
                <i class="fa fa-filter" aria-hidden="true"></i>
            </a>
            
            <div class="dropdown-menu md panel panel-default">
                <div class="panel-body text-center">
                    <ul class="">
                        <li><label><input type="checkbox" id="ancestral" class="options-check" value="0"><%t Genealogist.ANCESTRAL_TREE 'Ancestral Tree' %></label></li>
                        <li><label><input type="checkbox" id="m" class="options-check" value="0"><%t Genealogist.SHOW_MALES 'Show Males' %></label></li>
                        <li><label><input type="checkbox" id="ms" class="options-check" value="0"><%t Genealogist.SHOW_MALES_CHILDREN 'Show Males Children' %></label></li>
                        <% if hasPermission %>
                            <li><label><input type="checkbox" id="f" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES 'Show Females' %></label></li>
                            <li><label><input type="checkbox" id="fs" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES_CHILDREN 'Show Females Children' %></label></li>
                        <% end_if %>
                    </ul>
                </div>
            </div>
        </li>
        
        <li class="dropdown no-fullscreen">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="<%t Genealogist.KINSHIP 'Kinship' %>">
                <i class="fa fa-retweet" aria-hidden="true"></i>
            </a>

            <div class="dropdown-menu md panel panel-default">
                <div class="panel-heading">
                    <%t Genealogist.KINSHIP 'Kinship' %>
                </div>

                <div class="panel-body text-center">
                    $Form_Kinship
                </div>
            </div>
        </li>
        
        <li class="dropdown arrow arrow-top-center">
            <a href="/#" data-toggle="dropdown" class="icon-holder aside-disabled" title="<%t Genealogist.ROOTS_LIST 'Roots List' %>" aria-expanded="true">
                <span class="nav-icon"><i class="fa fa-code-fork"></i></span>
            </a>

            <div class="dropdown-menu md panel panel-default">
                <div class="panel-heading">
                    <input type="text" id="filter-input" class="filter-input" onkeyup="filterRoots()" placeholder="<%t Genealogist.ROOTS_SEARCH 'Search Roots' %>" />
                </div>

                <div class="panel-body text-center" style="max-height: 240px; overflow-y: auto;">
                    <ul id="filter-list" class="filter-list">
                        <% loop RootClans.Sort(Name, ASC) %>
                           <li><a href="{$TreeLink}" class="options-item">$ShortName</a></li>
                        <% end_loop %>
                    </ul>                       
                </div>
            </div>
        </li>      
    </ul>
</div>
