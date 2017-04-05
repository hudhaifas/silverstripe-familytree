<% if LandingPage %>
    <% include GenealogyContent %>
<% else %>
    <div id="tree-container" class="tree-container">
        <% include TheTree %>
    </div>
<% end_if %>