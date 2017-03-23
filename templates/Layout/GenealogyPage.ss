<% if $Tree %>
    <div id="tree-container" class="tree-container">
        <% include TheTree %>
    </div>
<% else %>
    <% include GenealogyContent %>
<% end_if %>