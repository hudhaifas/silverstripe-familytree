<div class="tree-container">
    <div class="tree-menu">
        <div class="panel-group" id="accordion">
            <% include Side_Roots %>

            <div class="panel panel-default" id="panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse"><%t FamilyTree.INFORMATION 'Information' %></a>
                    </h4>
                </div>
            </div>

            <%-- 
            <% include Side_Filters %>
            --%>
        </div>
    </div>

    <div id="tree-holder" class="tree-holder">
        <% include TheTree %>
    </div>
</div>