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

    <div id="tree-holder" class="col-md-9 col-sm-12">
        <% include TheTree %>
    </div>
</div>