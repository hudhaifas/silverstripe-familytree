<% with Gender %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>

    <div class="panel-body">
        <% if Sons %>
            <p>
                <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />

                <% loop Sons %>
                    <a href="{$EditLink(self)}" class="ajax-modal-nested" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                <% end_loop %>
            </p>
        <% end_if %>
        <p>
            <%t Genealogist.ADD_SONS 'Add Sons' %>
            $Up.Form_AddSons($ID)
        </p>
        
        <hr />
        
        <% if Daughters %>
            <p>
                <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />

                <% loop Daughters %>
                    <a href="{$EditLink(self)}" class="ajax-modal-nested" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                <% end_loop %>
            </p>
        <% end_if %>
        <p>
            <%t Genealogist.ADD_DAUGHTERS 'Add Daughters' %>
            $Up.Form_AddDaughters($ID)
        </p>
    </div>
</div>
<% end_with %>


