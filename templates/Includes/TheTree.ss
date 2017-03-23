<!--    
<div class="alert alert-info alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <strong><%t Genealogist.NOTE 'Note' %>: </strong>
    <%t Genealogist.SUGGEST_EDIT_MESSAGE_1 'Please forgive for the lack of accuracy of the data in this tree, ' %>
    <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
    <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
</div>
-->

<ul id="chart-list" style="display:none;" data-multiroot="{$MultiRoot}" data-collapsible="{$Collapsible}">
    $Tree
</ul>

<div id="chart-container"></div>

<div id="chart-loader" class="ajax-loader">
    <span></span>
    <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
</div>

<div style="display: none;">
    <% if ShowTimeline %>
        <% include TheTree_Timeline %>
    <% end_if %>

    <% include TheTree_Nav %>

    <% include TheTree_Controls %>
</div>