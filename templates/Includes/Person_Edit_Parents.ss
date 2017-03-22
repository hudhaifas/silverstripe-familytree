<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>

    <div class="panel-body">
        <% if $Father %>
            <p>
                <b><%t Genealogist.FATHER 'Father' %></b><br />
                <a href="{$Father.ObjectLink()}">$Father.FullName</a>
            </p>
        <% end_if %>

        <p>
            <%t Genealogist.ADD_FATHER 'Add Father' %>
            $Up.Form_AddFather($ID)
        </p>
        
        <p>
            <%t Genealogist.CAHNGE_FATHER 'Change Father' %>
            $Up.Form_ChangeFather($ID)
        </p>

        <hr />
        
        <% if Mother %>
            <p>
                <b><%t Genealogist.MOTHER 'Mother' %></b><br />

                <a href="{$Mother.ObjectLink()}">$Mother.FullName</a>
            </p>
        <% end_if %>

        <p>
            <%t Genealogist.CHANGE_MOTHER 'Change Mother' %>
            $Up.Form_ChangeMother($ID)
        </p>
    </div>
</div>
<% end_with %>

