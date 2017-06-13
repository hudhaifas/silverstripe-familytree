<% with Gender %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>

    <div class="panel-body">
        <% if not isClan %>
            <% if $Father %>
                <p>
                    <b><%t Genealogist.FATHER 'Father' %></b><br />
                    <a href="{$Father.EditLink(self)}" class="ajax-modal-nested">$Father.FullName</a>
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
        <% end_if %>
        
        <% if Mother %>
            <p>
                <b><%t Genealogist.MOTHER 'Mother' %></b><br />

                <a href="{$Mother.EditLink(self)}" class="ajax-modal-nested">$Mother.FullName</a>
            </p>
        <% end_if %>

        <% if not isClan %>
            <p>
                <%t Genealogist.CHANGE_MOTHER 'Change Mother' %>
                $Up.Form_ChangeMother($ID)
            </p>
        <% end_if %>
    </div>
</div>
<% end_with %>

