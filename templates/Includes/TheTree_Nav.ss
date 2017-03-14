<div id="menu-nav" class="navbar">
    <ul class="nav navbar-nav">        
        <li data-intro="<%t Genealogist.TUTORIAL_SEARCH_IN_TREE 'Searcg for person in the tree' %>" data-position="bottom">
            <a data-toggle="collapse" href="#nav-collapse3" aria-expanded="false" aria-controls="nav-collapse3" title="<%t Genealogist.SEARCH_IN_TREE 'Searcg in the tree' %>">
                <i class="fa fa-search" aria-hidden="true"></i>
            </a>
        </li>

        <li class="dropdown arrow arrow-top-center dropdown-label" data-intro="<%t Genealogist.TUTORIAL_ROOTS_LIST 'Choose a tree from the clans list' %>" data-position="bottom">
            <a href="/#" data-toggle="dropdown" class="icon-holder aside-disabled" title="<%t Genealogist.ROOTS_LIST 'Roots List' %>" aria-expanded="true">
                <span class="nav-icon"><i class="fa fa-code-fork"></i></span>
                <span class="hidden-phone"><%t Genealogist.ROOTS 'Clans' %></span>
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

        <li class="dropdown no-fullscreen" data-intro="<%t Genealogist.TUTORIAL_KINSHIP 'Find all kinship between any two persons, by typing few letters of their names and select from the popup menu' %>" data-position="bottom">
            <a id="kinsip-btn" class="dropdown-toggle" href="#" title="<%t Genealogist.KINSHIP 'Kinship' %>">
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

        <li class="dropdown" data-intro="<%t Genealogist.TUTORIAL_FILTERS 'Filter the tree results by check/unceck what to show' %>" data-position="bottom">
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

        <li class="no-timeline" data-intro="<%t Genealogist.TUTORIAL_HISTORY 'Play the history timeline on this tree and watch the people who have lived in each time period' %>" data-position="bottom" >
            <a id="timeline-btn" href="#" title="<%t Genealogist.TIMELINE 'Timeline' %>">
                <i class="fa fa-history" aria-hidden="true"></i>
            </a>
        </li>

    </ul>

    <div class="collapse nav navbar-nav nav-collapse" id="nav-collapse3">
        <form class="navbar-form navbar-right" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="<%t Genealogist.SEARCH_IN_TREE 'Searcg in the tree' %>" />
            </div>
        </form>
    </div>

</div>

<script>
    var introOpts = {
        showStepNumbers: false,
        nextLabel: '<%t Genealogist.TUTORIAL_NEXT "Next" %>',
        prevLabel: '<%t Genealogist.TUTORIAL_PREV "Prev" %>',
        skipLabel: '<%t Genealogist.TUTORIAL_SKIP "Skip" %>',
        doneLabel: '<%t Genealogist.TUTORIAL_DONE "Done" %>',
    };
</script>