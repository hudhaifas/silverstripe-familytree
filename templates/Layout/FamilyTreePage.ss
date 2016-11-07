<div class="conatiner-fluid">
    <div class="col-sm-3">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse2">Options</a>
                    </h4>
                </div>

                <!-- List group -->
                <ul id="collapse2" class="list-group panel-collapse collapse">
                    <li class="list-group-item">Option 1</li>
                    <li class="list-group-item">Option 2</li>
                </ul>
            </div>

            <div class="panel panel-default" id="panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse">Information</a>
                    </h4>
                </div>
            </div>

            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse3">Filters</a>
                    </h4>
                </div>

                <!-- List group -->
                <ul id="collapse3" class="list-group panel-collapse collapse">
                    <li class="list-group-item"><label><input type="checkbox" value="">Filter 1</label></li>
                    <li class="list-group-item"><label><input type="checkbox" value="">Filter 2</label></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <ul id="family-relations" style="display:none">
            <% loop Clans %>
            $HtmlUI
            <% end_loop %>
        </ul>
        <div class="dragscroll" id="family-tree"></div>
    </div>
</div>