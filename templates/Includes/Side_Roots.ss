<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><%t FamilyTree.ROOTS 'Roots' %></a>
        </h4>

        <div class="pull-right">
            <%--
            <a href="{$Link}" id="export-tree" title="<%t FamilyTree.EXPORT_PIC 'Export the tree in an image' %>"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            --%>
            <a href="#" id="toggle-fullscreen" title="<%t FamilyTree.FULLSCREEN 'Fullscreen' %>"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
        </div>
    </div>

    <div id="collapse1" class="list-group panel-collapse collapse in">
        <div class="panel-body">
            <% loop Roots.Sort(Name, ASC) %>
            <li class="list-group-item"><a href="{$Link}" class="options-item">$Name</a></li>
            <% end_loop %>
        </div>
    </div>
</div>