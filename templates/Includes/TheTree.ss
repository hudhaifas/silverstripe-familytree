<div class="alert alert-info alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <strong><%t Genealogist.NOTE 'Note' %>: </strong>
    <%t Genealogist.SUGGEST_EDIT_MESSAGE_1 'Please forgive for the lack of accuracy of the data in this tree, ' %>
    <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
    <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
</div>

<% loop Trees %>
    <ul id="genealogy-relations" class="genealogy-kinship" style="display:none" data-kinship="k{$Pos}">
        $Tree
    </ul>

    <div id="k{$Pos}" class="genealogy-tree dragscroll col-md-{$Up.Cols}"></div>
<% end_loop %>
    
<div id="chart-container" class="genealogy-tree dragscroll col-md-12"></div>

<div id="tree-loader" class="ajax-loader">
    <span></span>
    <img src="genealogist/images/ajax-loader.gif" alt="Loading Tree.." />
</div>