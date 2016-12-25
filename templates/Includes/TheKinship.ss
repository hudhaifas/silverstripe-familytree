<div class="alert alert-info alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <strong><%t Genealogist.BETA 'Beta' %>: </strong>
    <%t Genealogist.BETA_MESSAGE_1 'This is a beta version, ' %>
    <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
    <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
</div>

<% loop Roots %>
    <ul id="genealogy-relations" class="genealogy-kinship" style="display:none" data-kinship="k{$Pos}">
        $Kinship
    </ul>

    <div id="k{$Pos}" class="genealogy-tree dragscroll col-md-{$Up.Cols}"></div>
<% end_loop %>

<div id="tree-loader" class="ajax-loader">
    <span></span>
    <img src="genealogist/images/ajax-loader.gif" alt="Loading Tree.." />
</div>