<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#collapse2">Options</a>
        </h4>
    </div>

    <!-- List group -->
    <ul id="collapse2" class="list-group panel-collapse collapse">
        <% loop Roots %>
        <li class="list-group-item"><a href="{$Link}" class="options-item">$Name</a></li>
        <% end_loop %>
    </ul>
</div>