<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-roots"><%t Genealogist.ROOTS 'Roots' %></a>
        </h4>
    </div>

    <div id="collapse-roots" class="list-group panel-collapse collapse in">
        <div class="panel-body" style="max-height: 360px; overflow-y: auto;">
            <% loop Roots.Sort(Name, ASC) %>
            <div class="list-group-item"><a href="{$ShowLink}" class="options-item">$Name</a></div>
            <% end_loop %>
        </div>
    </div>
</div>