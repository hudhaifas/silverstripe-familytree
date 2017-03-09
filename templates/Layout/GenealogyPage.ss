<div class="tree-container">
    <div id="tree-holder" class="tree-holder">
        <% if Trees.Count %>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <strong><%t Genealogist.NOTE 'Note' %>: </strong>
                <%t Genealogist.SUGGEST_EDIT_MESSAGE_1 'Please forgive for the lack of accuracy of the data in this tree, ' %>
                <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
                <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
            </div>
            <% include TheTree %>

        <% else %>
            $Content
        <% end_if %>
    </div>
</div>