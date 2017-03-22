<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$ShortName</div>
    <div class="panel-body">
        <%t Genealogist.DELETE_PERSON 'Delete This Person' %>
        $Up.Form_DeletePerson($ID)
    </div>
</div>
<% end_with %>