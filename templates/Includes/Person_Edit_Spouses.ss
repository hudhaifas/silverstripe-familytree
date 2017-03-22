<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$ShortName</div>
    <div class="panel-body">
        <% if $isMale %>
            <%t Genealogist.ADD_WIFE 'Add Wife' %>
        <% else %>
            <%t Genealogist.ADD_HUSBAND 'Add Husband' %>
        <% end_if %>
        $Up.Form_AddSpouse($ID)

        <% if isMale %>
            <%t Genealogist.SINGLE_WIFE 'This Person Has One Wife Only' %>
            $Up.Form_SingleWife($ID)
        <% end_if %>
    </div>
</div>
<% end_with %>