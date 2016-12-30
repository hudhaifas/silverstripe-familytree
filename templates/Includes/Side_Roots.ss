<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-roots"><%t Genealogist.ROOTS 'Roots' %></a>
        </h4>

        <div class="pull-right">
            <%--
            <a href="{$ShowLink}" id="export-tree" title="<%t Genealogist.EXPORT_PIC 'Export the tree in an image' %>"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            --%>
            <a href="#" id="toggle-fullscreen" title="<%t Genealogist.FULLSCREEN 'Fullscreen' %>" class="hidden-phone hidden-tablet"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
        </div>
    </div>

    <div id="collapse-roots" class="list-group panel-collapse collapse in">
        <div class="panel-body" style="max-height: 360px; overflow-y: auto;">
            <% loop Roots.Sort(Name, ASC) %>
            <div class="list-group-item"><a href="{$ShowLink}" class="options-item">$Name</a></div>
            <% end_loop %>
        </div>
    </div>
</div>