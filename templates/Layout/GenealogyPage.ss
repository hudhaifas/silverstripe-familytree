<div class="tree-container row">
    <div class=" col-md-3 col-sm-12">
        <div class="panel-group" id="accordion">
            <% include Side_Roots %>

            <% include Side_Kinship %>

            <div class="panel panel-default" id="panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse"><%t Genealogist.INFORMATION 'Information' %></a>
                    </h4>
                </div>
            </div>

            <% include Side_Filters %>
        </div>
    </div>

    <div id="tree-holder" class="tree-holder col-md-9 col-sm-12">
        <div class="alert alert-info alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><%t Genealogist.NOTE 'Note' %>: </strong>
            <%t Genealogist.SUGGEST_EDIT_MESSAGE_1 'Please forgive for the lack of accuracy of the data in this tree, ' %>
            <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
            <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
        </div>

        <% if Trees.Count %>
            <% include TheTree %>
        <% else %>
            $Content
        <% end_if %>
    </div>
</div>