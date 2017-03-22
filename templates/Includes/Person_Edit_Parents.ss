<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$ShortName</div>
    <div class="panel-body">
        <%t Genealogist.ADD_FATHER 'Add Father' %>
        $Up.Form_AddFather($ID)

        <%t Genealogist.CAHNGE_FATHER 'Change Father' %>
        $Up.Form_ChangeFather($ID)

        <%t Genealogist.CHANGE_MOTHER 'Change Mother' %>
        $Up.Form_ChangeMother($ID)
    </div>
</div>
<% end_with %>

