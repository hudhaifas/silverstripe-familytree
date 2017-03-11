<div id="menu-nav" class="navbar">
    <ul class="nav navbar-nav">
        <li>
            <a id="timeline-btn" href="#" title="<%t Genealogist.TIMELINE 'Timeline' %>">
                <i class="fa fa-history" aria-hidden="true"></i>
            </a>
        </li>
        
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="<%t Genealogist.KINSHIP 'Kinship' %>">
                <i class="fa fa-retweet" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu">
                $Form_Kinship
            </ul>
        </li>
        
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="<%t Genealogist.FILTERS 'Filters' %>">
                <i class="fa fa-filter" aria-hidden="true"></i>
            </a>
            
            <ul class="dropdown-menu">
                <li><label><input type="checkbox" id="ancestral" class="options-check" value="0"><%t Genealogist.ANCESTRAL_TREE 'Ancestral Tree' %></label></li>
                <li><label><input type="checkbox" id="m" class="options-check" value="0"><%t Genealogist.SHOW_MALES 'Show Males' %></label></li>
                <li><label><input type="checkbox" id="ms" class="options-check" value="0"><%t Genealogist.SHOW_MALES_CHILDREN 'Show Males Children' %></label></li>
                <% if hasPermission %>
                    <li><label><input type="checkbox" id="f" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES 'Show Females' %></label></li>
                    <li><label><input type="checkbox" id="fs" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES_CHILDREN 'Show Females Children' %></label></li>
                <% end_if %>
            </ul>
        </li>
        
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="<%t Genealogist.ROOTS 'Roots' %>">
                <i class="fa fa-code-fork" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu">
                <% loop RootClans.Sort(Name, ASC) %>
                   <li><a href="{$TreeLink}" class="options-item">$ShortName</a></li>
                <% end_loop %>
            </ul>
        </li>
    </ul>
</div>
