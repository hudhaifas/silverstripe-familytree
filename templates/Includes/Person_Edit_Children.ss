<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$ShortName</div>
    <div class="panel-body">
        <% if isMale %>
            <%t Genealogist.ADD_SONS 'Add Sons' %>
            $Up.Form_AddSons($ID)
            <%t Genealogist.ADD_DAUGHTERS 'Add Daughters' %>
            $Up.Form_AddDaughters($ID)
        <% end_if %>
    </div>
</div>
<% end_with %>


