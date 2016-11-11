<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">
            <a data-toggle="collapse" href="#collapse2"><%t FamilyTree.ROOTS 'Roots' %></a>
        </h4>

        <div class="pull-right">
            <a href="{$Link}" id="preview-tree"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            <a href="{$Link}" id="export-tree"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
        </div>
    </div>

    <!-- List group -->
    <ul id="collapse2" class="list-group panel-collapse collapse">
        <% loop Roots %>
        <li class="list-group-item"><a href="{$Link}" class="options-item">$Name</a></li>
        <% end_loop %>
    </ul>
</div>