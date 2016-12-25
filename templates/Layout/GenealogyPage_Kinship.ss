<div class="tree-container row">
    <div class=" col-md-3 col-sm-12">
        <div class="panel-group" id="accordion">
            <% include Side_Roots %>

            <div class="panel panel-default" id="panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse"><%t Genealogist.INFORMATION 'Information' %></a>
                    </h4>
                </div>
            </div>

        </div>
    </div>

    <div id="tree-holder" class="col-md-9 col-sm-12">
        <div class="alert alert-info alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><%t Genealogist.BETA 'Beta' %>: </strong>
            <%t Genealogist.BETA_MESSAGE_1 'This is a beta version, ' %>
            <a href="$Link(suggest)" target="_blank"><u><%t Genealogist.CLICK_HERE 'click here' %></u></a> <i class="fa fa-comment" aria-hidden="true"></i>
            <%t Genealogist.SUGGEST_EDIT_MESSAGE_2 ' to provide us with any data.' value=$Link(suggest) %>
        </div>

        <ul id="genealogy-relations" style="display:none">
            $Leaves
        </ul>

        <div id="genealogy-tree" class="genealogy-tree dragscroll col-md-{$Cols}"></div>
    </div>
</div>