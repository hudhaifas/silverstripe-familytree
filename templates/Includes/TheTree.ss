<div style="display: none;">
    <% if ShowTimeline %>
        <% include TheTree_Timeline %>
    <% end_if %>

    <% include TheTree_Nav %>
    
    <% include TheTree_Controls %>
</div>

<% loop Trees %>
    <ul id="genealogy-relations" class="genealogy-kinship" style="display:none" data-kinship="k{$Pos}" data-multiple="{$Up.Multiple}">
        $Tree
    </ul>

    <div id="k{$Pos}" class="genealogy-tree dragscroll col-md-{$Up.Cols}"></div>
<% end_loop %>
    
<div id="tree-loader" class="ajax-loader">
    <span></span>
    <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
</div>